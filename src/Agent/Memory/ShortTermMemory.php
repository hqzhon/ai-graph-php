<?php

namespace App\Agent\Memory;

class ShortTermMemory implements MemoryInterface
{
    private $memory = [];
    
    public function add(string $key, $value): void
    {
        $this->memory[$key] = $value;
    }
    
    public function get(string $key, $default = null)
    {
        return $this->memory[$key] ?? $default;
    }
    
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->memory);
    }
    
    public function remove(string $key): void
    {
        unset($this->memory[$key]);
    }
    
    public function clear(): void
    {
        $this->memory = [];
    }
    
    /**
     * 获取所有记忆
     * 
     * @return array
     */
    public function getAll(): array
    {
        return $this->memory;
    }
}