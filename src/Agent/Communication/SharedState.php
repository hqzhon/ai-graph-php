<?php

namespace App\Agent\Communication;

use App\UnifiedGraph\State\State;

class SharedState
{
    private $state;
    
    public function __construct()
    {
        $this->state = new State();
    }
    
    /**
     * 获取状态值
     * 
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->state->get($key, $default);
    }
    
    /**
     * 设置状态值
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->state->set($key, $value);
    }
    
    /**
     * 更新状态（合并数组）
     * 
     * @param array $data 数据
     * @return void
     */
    public function update(array $data): void
    {
        $this->state->merge($data);
    }
    
    /**
     * 获取完整状态
     * 
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }
}