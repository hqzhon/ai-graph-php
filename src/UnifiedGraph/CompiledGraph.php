<?php

namespace App\UnifiedGraph;

use App\UnifiedGraph\State\StateInterface;
use App\UnifiedGraph\State\State;
use App\UnifiedGraph\Node\CallableNode;
use App\UnifiedGraph\Node\NodeInterface;

class CompiledGraph
{
    private $nodes;
    private $streamNodes;
    private $edges;
    private $conditionalEdges;
    private $entryPoint;
    private $finishPoints;
    private $stateClass;
    
    public function __construct(
        array $nodes,
        array $streamNodes, // Added
        array $edges,
        array $conditionalEdges,
        string $entryPoint,
        array $finishPoints,
        string $stateClass
    ) {
        $this->nodes = $nodes;
        $this->streamNodes = $streamNodes; // Added
        $this->edges = $edges;
        $this->conditionalEdges = $conditionalEdges;
        $this->entryPoint = $entryPoint;
        $this->finishPoints = $finishPoints;
        $this->stateClass = $stateClass;
    }
    
    /**
     * 执行图
     * 
     * @param StateInterface $state 初始状态
     * @return StateInterface 最终状态
     */
    public function execute(StateInterface $state): StateInterface
    {
        $finalState = null;
        foreach ($this->stream($state) as $finalState) {
            // loop until the end
        }
        return $finalState;
    }

    /**
     * 执行图并以流式返回每一步的状态
     *
     * @param StateInterface $state 初始状态
     * @return \Generator<StateInterface>
     */
    public function stream(StateInterface $state): \Generator
    {
        $currentNode = $this->entryPoint;
        $maxIterations = 100; // 防止无限循环
        $iterations = 0;

        while ($currentNode !== null && $iterations < $maxIterations) {
            $iterations++;

            // Check if it's a streaming node
            if (isset($this->streamNodes[$currentNode])) {
                $action = $this->streamNodes[$currentNode];
                $generator = $action($state->getData());
                yield from $generator;
                // After streaming, the last yielded value should be the final state from that node.
                $state = $generator->getReturn() ?? $state; // In case the generator doesn't have a return value

            } elseif (isset($this->nodes[$currentNode])) {
                $action = $this->nodes[$currentNode];
                
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
                
                $state->merge(['_currentNode' => $currentNode]);
                yield $state;
                
                // If this is a finish point, we're done
                if (isset($this->finishPoints[$currentNode])) {
                    break;
                }
            }

            $currentNode = $this->getNextNode($currentNode, $state);
        }

        if ($iterations >= $maxIterations) {
            throw new \RuntimeException("Maximum iterations reached ($maxIterations). Possible infinite loop detected.");
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