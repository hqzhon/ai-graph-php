<?php

namespace UnifiedGraph\State;

class BinaryOperatorAggregateChannel implements ChannelInterface
{
    protected $key;
    protected $value;
    protected $operator;
    
    public function __construct(string $key, callable $operator, $initialValue = null)
    {
        $this->key = $key;
        $this->operator = $operator;
        $this->value = $initialValue;
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
    
    public function get()
    {
        return $this->value;
    }
    
    public function update($value): void
    {
        $this->value = call_user_func($this->operator, $this->value, $value);
    }
    
    public function merge($value): void
    {
        // For BinaryOperatorAggregateChannel, merge is the same as update
        $this->update($value);
    }
}