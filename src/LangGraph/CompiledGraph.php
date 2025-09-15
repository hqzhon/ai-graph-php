<?php

namespace App\LangGraph;

use App\Graph\State\StateInterface;
use App\Graph\State\State;

class CompiledGraph
{
    private $nodes;
    private $edges;
    private $conditionalEdges;
    private $entryPoint;
    private $finishPoints;
    private $stateClass;
    
    public function __construct(
        array $nodes,
        array $edges,
        array $conditionalEdges,
        string $entryPoint,
        array $finishPoints,
        string $stateClass
    ) {
        $this->nodes = $nodes;
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
        $currentNode = $this->entryPoint;
        $visitedNodes = [];
        $maxIterations = 100; // 防止无限循环
        $iterations = 0;
        
        while ($currentNode !== null && $iterations < $maxIterations) {
            $iterations++;
            
            // 检查是否是结束节点
            if (isset($this->finishPoints[$currentNode])) {
                break;
            }
            
            // 执行当前节点
            if (isset($this->nodes[$currentNode])) {
                $action = $this->nodes[$currentNode];
                $stateData = $action($state->getData());
                
                // 更新状态
                if ($stateData !== null) {
                    if (is_array($stateData)) {
                        $state->merge($stateData);
                    } elseif ($stateData instanceof StateInterface) {
                        $state = $stateData;
                    }
                }
            }
            
            // 确定下一个节点
            $currentNode = $this->getNextNode($currentNode, $state);
        }
        
        // 如果达到最大迭代次数，抛出异常
        if ($iterations >= $maxIterations) {
            throw new \RuntimeException("Maximum iterations reached ($maxIterations). Possible infinite loop detected.");
        }
        
        return $state;
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