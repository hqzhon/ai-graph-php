<?php

namespace App\UnifiedGraph\Checkpoint;

use App\UnifiedGraph\State\StateInterface;

class MemoryCheckpointSaver implements CheckpointSaverInterface
{
    /**
     * @var array
     */
    protected $checkpoints = [];
    
    /**
     * 保存检查点
     * 
     * @param string $threadId 线程ID
     * @param string $checkpointId 检查点ID
     * @param StateInterface $state 状态
     * @return void
     */
    public function save(string $threadId, string $checkpointId, StateInterface $state): void
    {
        if (!isset($this->checkpoints[$threadId])) {
            $this->checkpoints[$threadId] = [];
        }
        
        // 对于包含闭包的对象，我们需要特殊处理
        try {
            $this->checkpoints[$threadId][$checkpointId] = serialize($state);
        } catch (\Exception $e) {
            // 如果序列化失败，我们存储状态数据而不是整个对象
            $this->checkpoints[$threadId][$checkpointId] = $state->getData();
        }
    }
    
    /**
     * 获取检查点
     * 
     * @param string $threadId 线程ID
     * @param string $checkpointId 检查点ID
     * @return StateInterface|array|null
     */
    public function get(string $threadId, string $checkpointId)
    {
        if (isset($this->checkpoints[$threadId][$checkpointId])) {
            $data = $this->checkpoints[$threadId][$checkpointId];
            
            // 如果是序列化的对象，尝试反序列化
            if (is_string($data) && strpos($data, 'C:') === 0) {
                try {
                    return unserialize($data);
                } catch (\Exception $e) {
                    // 如果反序列化失败，返回null
                    return null;
                }
            }
            
            // 如果是数组数据，直接返回
            return $data;
        }
        
        return null;
    }
    
    /**
     * 列出线程的所有检查点
     * 
     * @param string $threadId 线程ID
     * @return array
     */
    public function list(string $threadId): array
    {
        return isset($this->checkpoints[$threadId]) ? array_keys($this->checkpoints[$threadId]) : [];
    }
}