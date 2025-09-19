<?php

namespace App\UnifiedGraph;

use App\UnifiedGraph\Node\NodeInterface;
use App\UnifiedGraph\Exception\GraphValidationException;

abstract class BaseGraph implements GraphInterface
{
    protected $nodes = [];
    protected $streamNodes = [];
    protected $edges = [];
    protected $conditionalEdges = [];
    protected $entryPoint = null;
    protected $finishPoints = [];
    
    public function addNode(string $key, $action): self
    {
        // Support both callable functions and NodeInterface objects
        if (!is_callable($action) && !$action instanceof NodeInterface) {
            throw new \InvalidArgumentException('Action must be callable or implement NodeInterface');
        }
        
        $this->nodes[$key] = $action;
        return $this;
    }
    
    public function addEdge(string $start, string $end): self
    {
        if (!isset($this->edges[$start])) {
            $this->edges[$start] = [];
        }
        
        $this->edges[$start][] = $end;
        return $this;
    }
    
    public function addConditionalEdges(string $start, callable $condition, array $mapping): self
    {
        $this->conditionalEdges[$start] = [
            'condition' => $condition,
            'mapping' => $mapping
        ];
        
        return $this;
    }
    
    public function setEntryPoint(string $key): self
    {
        $this->entryPoint = $key;
        return $this;
    }
    
    public function setFinishPoint(string $key): self
    {
        $this->finishPoints[$key] = true;
        return $this;
    }
    
    protected function validateGraph(): void
    {
        // 检查起始节点是否存在
        if ($this->entryPoint === null) {
            throw new GraphValidationException('Entry point not set');
        }
        
        if (!isset($this->nodes[$this->entryPoint])) {
            throw new GraphValidationException("Entry point node '{$this->entryPoint}' not found");
        }
        
        // 检查所有边的节点是否存在
        foreach ($this->edges as $start => $ends) {
            if (!isset($this->nodes[$start])) {
                throw new GraphValidationException("Node '$start' not found for edge");
            }
            
            foreach ($ends as $end) {
                if (!isset($this->nodes[$end]) && !isset($this->finishPoints[$end])) {
                    throw new GraphValidationException("Node '$end' not found for edge");
                }
            }
        }
        
        // 检查条件边的节点是否存在
        foreach ($this->conditionalEdges as $start => $edge) {
            if (!isset($this->nodes[$start])) {
                throw new GraphValidationException("Node '$start' not found for conditional edge");
            }
            
            foreach ($edge['mapping'] as $conditionResult => $end) {
                if (!isset($this->nodes[$end]) && !isset($this->finishPoints[$end])) {
                    throw new GraphValidationException("Node '$end' not found for conditional edge mapping");
                }
            }
        }
    }
    
    abstract public function compile(): CompiledGraph;
}