<?php

namespace App\UnifiedGraph;

class StateGraph extends BaseGraph
{
    private $stateClass;
    
    public function __construct(string $stateClass)
    {
        $this->stateClass = $stateClass;
    }
    
    public function getStateClass(): string
    {
        return $this->stateClass;
    }
    
    public function addStreamNode(string $key, callable $action): self
    {
        $this->streamNodes[$key] = $action;
        return $this;
    }

    public function compile(): CompiledGraph
    {
        // 验证图的完整性
        $this->validateGraph();
        
        // 创建编译后的图
        return new CompiledGraph(
            $this->nodes,
            $this->streamNodes, // Pass stream nodes
            $this->edges,
            $this->conditionalEdges,
            $this->entryPoint,
            $this->finishPoints,
            $this->stateClass
        );
    }
    
    private function validateGraph(): void
    {
        // 检查起始节点是否存在
        if ($this->entryPoint === null) {
            throw new \RuntimeException('Entry point not set');
        }
        
        if (!isset($this->nodes[$this->entryPoint])) {
            throw new \RuntimeException("Entry point node '{$this->entryPoint}' not found");
        }
        
        // 检查所有边的节点是否存在
        foreach ($this->edges as $start => $ends) {
            if (!isset($this->nodes[$start])) {
                throw new \RuntimeException("Node '$start' not found for edge");
            }
            
            foreach ($ends as $end) {
                if (!isset($this->nodes[$end]) && !isset($this->finishPoints[$end])) {
                    throw new \RuntimeException("Node '$end' not found for edge");
                }
            }
        }
        
        // 检查条件边的节点是否存在
        foreach ($this->conditionalEdges as $start => $edge) {
            if (!isset($this->nodes[$start])) {
                throw new \RuntimeException("Node '$start' not found for conditional edge");
            }
            
            foreach ($edge['mapping'] as $conditionResult => $end) {
                if (!isset($this->nodes[$end]) && !isset($this->finishPoints[$end])) {
                    throw new \RuntimeException("Node '$end' not found for conditional edge mapping");
                }
            }
        }
    }
}