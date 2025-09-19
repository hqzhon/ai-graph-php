<?php

namespace App\UnifiedGraph\Edge;

interface EdgeInterface
{
    /**
     * 获取边的起点节点名称
     * 
     * @return string
     */
    public function getFrom(): string;
    
    /**
     * 获取边的终点节点名称
     * 
     * @return string
     */
    public function getTo(): string;
    
    /**
     * 检查边是否可以被激活（基于当前状态）
     * 
     * @param array $state 当前状态
     * @return bool
     */
    public function canActivate(array $state): bool;
}