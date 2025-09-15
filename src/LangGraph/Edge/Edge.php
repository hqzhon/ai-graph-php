<?php

namespace App\LangGraph\Edge;

class Edge
{
    private $start;
    private $end;
    
    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    
    public function getStart(): string
    {
        return $this->start;
    }
    
    public function getEnd(): string
    {
        return $this->end;
    }
}