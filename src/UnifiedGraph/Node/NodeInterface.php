<?php

namespace App\UnifiedGraph\Node;

interface NodeInterface
{
    /**
     * 获取节点名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 执行节点逻辑
     * 
     * @param array $state 当前状态
     * @return array 更新后的状态
     */
    public function execute(array $state): array;
}