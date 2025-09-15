<?php

namespace App\Agent\Memory;

interface MemoryInterface
{
    /**
     * 添加记忆
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return void
     */
    public function add(string $key, $value): void;
    
    /**
     * 获取记忆
     * 
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null);
    
    /**
     * 检查记忆是否存在
     * 
     * @param string $key 键名
     * @return bool
     */
    public function has(string $key): bool;
    
    /**
     * 删除记忆
     * 
     * @param string $key 键名
     * @return void
     */
    public function remove(string $key): void;
    
    /**
     * 清空所有记忆
     * 
     * @return void
     */
    public function clear(): void;
}