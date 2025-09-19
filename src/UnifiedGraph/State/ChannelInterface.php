<?php

namespace App\UnifiedGraph\State;

interface ChannelInterface
{
    /**
     * 获取通道的键名
     * 
     * @return string
     */
    public function getKey(): string;
    
    /**
     * 获取当前值
     * 
     * @return mixed
     */
    public function get();
    
    /**
     * 更新通道的值
     * 
     * @param mixed $value 新值
     * @return void
     */
    public function update($value): void;
    
    /**
     * 合并值（用于支持合并操作的通道类型）
     * 
     * @param mixed $value 要合并的值
     * @return void
     */
    public function merge($value): void;
}