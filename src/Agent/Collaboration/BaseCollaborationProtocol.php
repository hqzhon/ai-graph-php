<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

abstract class BaseCollaborationProtocol implements CollaborationProtocolInterface
{
    protected $name;
    
    public function __construct(string $name = "BaseCollaborationProtocol")
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * 初始化协作状态
     * 
     * @param string $task 协作任务
     * @return State 初始状态
     */
    protected function initializeState(string $task): State
    {
        return new State([
            'task' => $task,
            'phase' => 'initialization',
            'agents' => [],
            'communications' => [],
            'decisions' => [],
            'context' => []
        ]);
    }
    
    /**
     * 更新协作状态
     * 
     * @param State $state 当前状态
     * @param array $updates 更新数据
     * @return State 更新后的状态
     */
    protected function updateState(State $state, array $updates): State
    {
        $state->merge($updates);
        return $state;
    }
}