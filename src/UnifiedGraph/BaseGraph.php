<?php

namespace App\UnifiedGraph;

use App\UnifiedGraph\Node\NodeInterface;

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
    
    abstract public function compile(): CompiledGraph;
}