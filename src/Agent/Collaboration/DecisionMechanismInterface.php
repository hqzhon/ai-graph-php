<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

interface DecisionMechanismInterface
{
    /**
     * 做出决策
     * 
     * @param array $agents 参与决策的智能体
     * @param string $decisionPoint 决策点
     * @param GraphState $state 当前状态
     * @return mixed 决策结果
     */
    public function makeDecision(array $agents, string $decisionPoint, GraphState $state);
    
    /**
     * 收集智能体意见
     * 
     * @param array $agents 智能体列表
     * @param string $topic 议题
     * @param GraphState $state 当前状态
     * @return array 意见列表
     */
    public function collectOpinions(array $agents, string $topic, GraphState $state): array;
    
    /**
     * 整合意见
     * 
     * @param array $opinions 意见列表
     * @param GraphState $state 当前状态
     * @return mixed 整合结果
     */
    public function integrateOpinions(array $opinions, GraphState $state);
}