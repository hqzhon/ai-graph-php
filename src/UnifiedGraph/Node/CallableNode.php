<?php

namespace UnifiedGraph\Node;

class CallableNode extends AbstractNode
{
    protected $callable;
    
    public function __construct(string $name, callable $callable)
    {
        parent::__construct($name);
        $this->callable = $callable;
    }
    
    protected function process(array $state): array
    {
        $result = call_user_func($this->callable, $state);
        
        // If the result is an array, merge it with the state
        if (is_array($result)) {
            return array_merge($state, $result);
        }
        
        // If the result is null, return the original state
        if ($result === null) {
            return $state;
        }
        
        // For other types, we might want to handle them differently
        // For now, we'll just return the original state
        return $state;
    }
}