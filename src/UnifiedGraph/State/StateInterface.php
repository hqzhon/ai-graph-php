<?php

namespace App\UnifiedGraph\State;

interface StateInterface
{
    /**
     * 获取状态数据
     * 
     * @return array
     */
    public function getData(): array;
    
    /**
     * 设置状态数据
     * 
     * @param array $data 状态数据
     * @return void
     */
    public function setData(array $data): void;
    
    /**
     * 获取状态中的特定值
     * 
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null);
    
    /**
     * 设置状态中的特定值
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function set(string $key, $value): void;
    
    /**
     * 检查状态中是否存在特定键
     * 
     * @param string $key 键名
     * @return bool
     */
    public function has(string $key): bool;
    
    /**
     * 合并状态数据
     * 
     * @param array $data 要合并的数据
     * @return void
     */
    public function merge(array $data): void;
}