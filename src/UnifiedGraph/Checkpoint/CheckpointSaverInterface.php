<?php

namespace App\UnifiedGraph\Checkpoint;

use App\UnifiedGraph\State\StateInterface;

interface CheckpointSaverInterface
{
    /**
     * 保存检查点
     * 
     * @param string $threadId 线程ID
     * @param string $checkpointId 检查点ID
     * @param StateInterface $state 状态
     * @return void
     */
    public function save(string $threadId, string $checkpointId, StateInterface $state): void;
    
    /**
     * 获取检查点
     * 
     * @param string $threadId 线程ID
     * @param string $checkpointId 检查点ID
     * @return StateInterface|array|null
     */
    public function get(string $threadId, string $checkpointId);
    
    /**
     * 列出线程的所有检查点
     * 
     * @param string $threadId 线程ID
     * @return array
     */
    public function list(string $threadId): array;
}