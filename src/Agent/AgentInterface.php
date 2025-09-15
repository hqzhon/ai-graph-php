<?php

namespace App\Agent;

use App\LangGraph\State\GraphState;

interface AgentInterface
{
    /**
     * 获取智能体名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 智能体执行动作
     * 
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    public function act(GraphState $state): GraphState;
    
    /**
     * 获取智能体描述
     * 
     * @return string
     */
    public function getDescription(): string;
}