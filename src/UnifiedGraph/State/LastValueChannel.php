<?php

namespace UnifiedGraph\State;

class LastValueChannel implements ChannelInterface
{
    protected $key;
    protected $value;
    
    public function __construct(string $key, $initialValue = null)
    {
        $this->key = $key;
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
        $this->value = $value;
    }
    
    public function merge($value): void
    {
        // For LastValueChannel, merge is the same as update
        $this->update($value);
    }
}