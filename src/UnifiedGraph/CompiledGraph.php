<?php

namespace App\UnifiedGraph;

use App\UnifiedGraph\State\StateInterface;
use App\UnifiedGraph\State\State;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\Node\CallableNode;
use App\UnifiedGraph\Node\NodeInterface;
use App\UnifiedGraph\Checkpoint\CheckpointSaverInterface;
use App\UnifiedGraph\Exception\LangGraphException;
use App\UnifiedGraph\Exception\NodeExecutionException;
use App\UnifiedGraph\Exception\InterruptedException;

class CompiledGraph
{
    private $nodes;
    private $streamNodes;
    private $edges;
    private $conditionalEdges;
    private $entryPoint;
    private $finishPoints;
    private $stateClass;
    private $channels;
    private $checkpointSaver;
    
    public function __construct(
        array $nodes,
        array $streamNodes, // Added
        array $edges,
        array $conditionalEdges,
        string $entryPoint,
        array $finishPoints,
        string $stateClass,
        array $channels = [],
        ?CheckpointSaverInterface $checkpointSaver = null
    ) {
        $this->nodes = $nodes;
        $this->streamNodes = $streamNodes; // Added
        $this->edges = $edges;
        $this->conditionalEdges = $conditionalEdges;
        $this->entryPoint = $entryPoint;
        $this->finishPoints = $finishPoints;
        $this->stateClass = $stateClass;
        $this->channels = $channels;
        $this->checkpointSaver = $checkpointSaver;
    }
    
    /**
     * 执行图
     * 
     * @param StateInterface $state 初始状态
     * @param string|null $threadId 线程ID，用于检查点
     * @param array $interruptBefore 在执行前中断的节点列表
     * @param array $interruptAfter 在执行后中断的节点列表
     * @return StateInterface 最终状态
     * @throws LangGraphException
     */
    public function execute(
        StateInterface $state,
        ?string $threadId = null,
        array $interruptBefore = [],
        array $interruptAfter = []
    ): StateInterface {
        $finalState = null;
        try {
            foreach ($this->stream($state, $threadId, $interruptBefore, $interruptAfter) as $finalState) {
                // loop until the end
            }
        } catch (InterruptedException $e) {
            // 重新抛出中断异常
            throw $e;
        } catch (LangGraphException $e) {
            // 重新抛出自定义异常
            throw $e;
        } catch (\Throwable $e) {
            // 包装其他异常
            throw new LangGraphException("Error executing graph", [
                'error' => $e->getMessage(),
                'previous' => $e
            ], 0, $e);
        }
        return $finalState;
    }

    /**
     * 执行图并以流式返回每一步的状态
     *
     * @param StateInterface $state 初始状态
     * @param string|null $threadId 线程ID，用于检查点
     * @param array $interruptBefore 在执行前中断的节点列表
     * @param array $interruptAfter 在执行后中断的节点列表
     * @return \Generator<StateInterface>
     * @throws LangGraphException
     */
    public function stream(
        StateInterface $state, 
        ?string $threadId = null,
        array $interruptBefore = [],
        array $interruptAfter = []
    ): \Generator {
        // 如果定义了通道且使用的是ChannelsState，则创建带通道的状态
        if (!empty($this->channels) && $state instanceof State && $this->stateClass === ChannelsState::class) {
            $state = new ChannelsState($this->channels, $state->getData());
        }
        
        $currentNode = $this->entryPoint;
        $maxIterations = 100; // 防止无限循环
        $iterations = 0;
        $startTime = microtime(true);
        $timeout = 30; // 默认30秒超时

        while ($currentNode !== null && $iterations < $maxIterations) {
            $iterations++;
            
            // 检查超时
            if (microtime(true) - $startTime > $timeout) {
                throw new LangGraphException("Execution timeout reached ({$timeout} seconds).", [
                    'timeout' => $timeout,
                    'elapsed' => microtime(true) - $startTime,
                    'iterations' => $iterations
                ]);
            }

            // 检查是否需要在执行前中断
            if (in_array($currentNode, $interruptBefore)) {
                // 保存检查点
                if ($this->checkpointSaver && $threadId) {
                    $checkpointId = uniqid('checkpoint_', true);
                    $this->checkpointSaver->save($threadId, $checkpointId, $state);
                }
                
                // 抛出中断异常
                throw new InterruptedException($currentNode, 'before', "Interrupted before node: {$currentNode}");
            }

            // Check if it's a streaming node
            if (isset($this->streamNodes[$currentNode])) {
                $action = $this->streamNodes[$currentNode];
                try {
                    $generator = $action($state->getData());
                    yield from $generator;
                    // After streaming, the last yielded value should be the final state from that node.
                    $state = $generator->getReturn() ?? $state; // In case the generator doesn't have a return value
                } catch (\Throwable $e) {
                    throw new NodeExecutionException($currentNode, "Error executing streaming node: {$currentNode}", [
                        'node' => $currentNode,
                        'error' => $e->getMessage()
                    ], 0, $e);
                }

            } elseif (isset($this->nodes[$currentNode])) {
                $action = $this->nodes[$currentNode];
                
                try {
                    if (is_callable($action)) {
                        $stateData = $action($state->getData());
                        if ($stateData !== null) {
                            if (is_array($stateData)) {
                                $state->merge($stateData);
                            } elseif ($stateData instanceof StateInterface) {
                                $state = $stateData;
                            }
                        }
                    } elseif ($action instanceof NodeInterface) {
                        $stateData = $action->execute($state->getData());
                        $state->setData($stateData);
                    }
                } catch (\Throwable $e) {
                    throw new NodeExecutionException($currentNode, "Error executing node: {$currentNode}", [
                        'node' => $currentNode,
                        'error' => $e->getMessage()
                    ], 0, $e);
                }
                
                $state->merge(['_currentNode' => $currentNode]);
                yield $state;
                
                // 检查是否需要在执行后中断
                if (in_array($currentNode, $interruptAfter)) {
                    // 保存检查点
                    if ($this->checkpointSaver && $threadId) {
                        $checkpointId = uniqid('checkpoint_', true);
                        $this->checkpointSaver->save($threadId, $checkpointId, $state);
                    }
                    
                    // 抛出中断异常
                    throw new InterruptedException($currentNode, 'after', "Interrupted after node: {$currentNode}");
                }
                
                // If this is a finish point, we're done
                if (isset($this->finishPoints[$currentNode])) {
                    break;
                }
            }

            $currentNode = $this->getNextNode($currentNode, $state);
        }

        if ($iterations >= $maxIterations) {
            throw new LangGraphException("Maximum iterations reached ($maxIterations). Possible infinite loop detected.", [
                'maxIterations' => $maxIterations,
                'iterations' => $iterations
            ]);
        }
    }
    
    /**
     * 获取下一个节点
     * 
     * @param string $currentNode 当前节点
     * @param StateInterface $state 当前状态
     * @return string|null 下一个节点，如果结束则返回null
     */
    private function getNextNode(string $currentNode, StateInterface $state): ?string
    {
        // 首先检查条件边
        if (isset($this->conditionalEdges[$currentNode])) {
            $conditionalEdge = $this->conditionalEdges[$currentNode];
            $condition = $conditionalEdge['condition'];
            $mapping = $conditionalEdge['mapping'];
            
            // 执行条件函数
            $conditionResult = $condition($state->getData());
            
            // 根据条件结果映射到下一个节点
            if (isset($mapping[$conditionResult])) {
                return $mapping[$conditionResult];
            }
            
            // 如果没有匹配的条件，检查是否有默认映射
            if (isset($mapping['default'])) {
                return $mapping['default'];
            }
        }
        
        // 然后检查普通边
        if (isset($this->edges[$currentNode])) {
            // 对于普通边，我们简单地返回第一个目标节点
            // 在更复杂的实现中，可能需要支持多个目标节点的处理
            return $this->edges[$currentNode][0] ?? null;
        }
        
        // 没有找到下一个节点，返回null表示结束
        return null;
    }
}