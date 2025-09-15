<?php

namespace App\Graph\Edge;

class Edge implements EdgeInterface
{
    protected $from;
    protected $to;
    protected $condition;
    
    public function __construct(string $from, string $to, ?callable $condition = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->condition = $condition;
    }
    
    public function getFrom(): string
    {
        return $this->from;
    }
    
    public function getTo(): string
    {
        return $this->to;
    }
    
    public function canActivate(array $state): bool
    {
        // 如果没有条件，边总是可以激活
        if ($this->condition === null) {
            return true;
        }
        
        // 调用条件回调函数
        return call_user_func($this->condition, $state);
    }
}