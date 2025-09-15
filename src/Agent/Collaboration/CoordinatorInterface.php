<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

interface CoordinatorInterface
{
    /**
     * 协调智能体活动
     * 
     * @param array $agents 智能体列表
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    public function coordinate(array $agents, GraphState $state): GraphState;
    
    /**
     * 处理智能体间的依赖关系
     * 
     * @param array $dependencies 依赖关系
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    public function handleDependencies(array $dependencies, GraphState $state): GraphState;
    
    /**
     * 调整智能体优先级
     * 
     * @param array $agents 智能体列表
     * @param GraphState $state 当前状态
     * @return array 调整后的智能体列表
     */
    public function adjustPriorities(array $agents, GraphState $state): array;
}