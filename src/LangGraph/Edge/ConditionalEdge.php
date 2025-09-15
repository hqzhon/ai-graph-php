<?php

namespace App\LangGraph\Edge;

class ConditionalEdge
{
    private $start;
    private $condition;
    private $mapping;
    
    public function __construct(string $start, callable $condition, array $mapping)
    {
        $this->start = $start;
        $this->condition = $condition;
        $this->mapping = $mapping;
    }
    
    public function getStart(): string
    {
        return $this->start;
    }
    
    public function getCondition(): callable
    {
        return $this->condition;
    }
    
    public function getMapping(): array
    {
        return $this->mapping;
    }
}