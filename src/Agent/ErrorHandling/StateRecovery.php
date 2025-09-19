<?php

namespace App\Agent\ErrorHandling;

use App\UnifiedGraph\State\State;

class StateRecovery
{
    private $checkpointDirectory;
    
    public function __construct(string $checkpointDirectory = '/tmp/agent_checkpoints')
    {
        $this->checkpointDirectory = $checkpointDirectory;
        
        // 确保检查点目录存在
        if (!is_dir($this->checkpointDirectory)) {
            mkdir($this->checkpointDirectory, 0755, true);
        }
    }
    
    /**
     * 保存状态检查点
     * 
     * @param string $checkpointId 检查点ID
     * @param State $state 状态
     * @return void
     */
    public function saveCheckpoint(string $checkpointId, State $state): void
    {
        $filename = $this->checkpointDirectory . '/' . $checkpointId . '.json';
        $data = json_encode($state->getData());
        file_put_contents($filename, $data);
    }
    
    /**
     * 恢复状态检查点
     * 
     * @param string $checkpointId 检查点ID
     * @return State|null
     */
    public function restoreCheckpoint(string $checkpointId): ?State
    {
        $filename = $this->checkpointDirectory . '/' . $checkpointId . '.json';
        
        if (!file_exists($filename)) {
            return null;
        }
        
        $data = file_get_contents($filename);
        $stateData = json_decode($data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        return new State($stateData);
    }
    
    /**
     * 删除检查点
     * 
     * @param string $checkpointId 检查点ID
     * @return void
     */
    public function deleteCheckpoint(string $checkpointId): void
    {
        $filename = $this->checkpointDirectory . '/' . $checkpointId . '.json';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}