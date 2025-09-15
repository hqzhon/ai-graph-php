<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

interface TaskAllocatorInterface
{
    /**
     * 分配任务
     * 
     * @param string $task 任务描述
     * @param array $agents 可用的智能体
     * @param GraphState $state 当前状态
     * @return string|null 被分配的智能体名称
     */
    public function allocateTask(string $task, array $agents, GraphState $state): ?string;
    
    /**
     * 重新分配任务
     * 
     * @param string $task 任务描述
     * @param array $agents 可用的智能体
     * @param GraphState $state 当前状态
     * @return string|null 新的被分配的智能体名称
     */
    public function reallocateTask(string $task, array $agents, GraphState $state): ?string;
    
    /**
     * 获取智能体负载
     * 
     * @param AgentInterface $agent 智能体
     * @return float 负载值（0-1）
     */
    public function getAgentLoad(AgentInterface $agent): float;
}