<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

interface SwarmIntelligenceInterface
{
    /**
     * 初始化群体智能
     * 
     * @param array $agents 智能体列表
     * @return void
     */
    public function initialize(array $agents): void;
    
    /**
     * 模拟群体行为
     * 
     * @param array $agents 智能体列表
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    public function simulateSwarmBehavior(array $agents, GraphState $state): GraphState;
    
    /**
     * 优化群体解决方案
     * 
     * @param array $solutions 解决方案列表
     * @param GraphState $state 当前状态
     * @return mixed 优化后的解决方案
     */
    public function optimizeSolutions(array $solutions, GraphState $state);
    
    /**
     * 评估群体性能
     * 
     * @param array $agents 智能体列表
     * @param GraphState $state 当前状态
     * @return array 性能指标
     */
    public function evaluatePerformance(array $agents, GraphState $state): array;
}