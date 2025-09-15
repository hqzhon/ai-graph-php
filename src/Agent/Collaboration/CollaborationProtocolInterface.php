<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

interface CollaborationProtocolInterface
{
    /**
     * 初始化协作协议
     * 
     * @param array $agents 参与的智能体
     * @return void
     */
    public function initialize(array $agents): void;
    
    /**
     * 智能体请求协作
     * 
     * @param AgentInterface $agent 请求的智能体
     * @param string $request 请求内容
     * @param GraphState $state 当前状态
     * @return mixed 协作结果
     */
    public function requestCollaboration(AgentInterface $agent, string $request, GraphState $state);
    
    /**
     * 分配任务给智能体
     * 
     * @param string $task 任务描述
     * @param array $eligibleAgents 合适的智能体列表
     * @param GraphState $state 当前状态
     * @return string|null 被选中的智能体名称
     */
    public function assignTask(string $task, array $eligibleAgents, GraphState $state): ?string;
    
    /**
     * 协调智能体间的冲突
     * 
     * @param array $conflict 冲突信息
     * @param GraphState $state 当前状态
     * @return bool 是否解决冲突
     */
    public function resolveConflict(array $conflict, GraphState $state): bool;
}