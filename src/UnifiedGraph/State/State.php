<?php

namespace UnifiedGraph\State;

class State implements StateInterface
{
    protected $data = [];
    
    public function __construct(array $initialData = [])
    {
        $this->data = $initialData;
    }
    
    public function getData(): array
    {
        return $this->data;
    }
    
    public function setData(array $data): void
    {
        $this->data = $data;
    }
    
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }
    
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
    
    /**
     * 合并状态数据
     * 
     * @param array $data 要合并的数据
     * @return void
     */
    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }
    
    /**
     * 更新状态
     * 
     * @param array $updates 要更新的数据
     * @return void
     */
    public function update(array $updates): void
    {
        foreach ($updates as $key => $value) {
            $this->data[$key] = $value;
        }
    }
}