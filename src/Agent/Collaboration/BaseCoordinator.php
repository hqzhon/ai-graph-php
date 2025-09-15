<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class BaseCoordinator implements CoordinatorInterface
{
    public function coordinate(array $agents, GraphState $state): GraphState
    {
        // 基础协调逻辑：
        // 1. 检查智能体状态
        // 2. 确保没有冲突的活动
        // 3. 更新状态
        
        $state->set('coordination_timestamp', microtime(true));
        $state->set('active_agents', array_map(function($agent) {
            return $agent->getName();
        }, $agents));
        
        return $state;
    }
    
    public function handleDependencies(array $dependencies, GraphState $state): GraphState
    {
        // 处理依赖关系
        $state->set('dependencies', $dependencies);
        $state->set('dependencies_handled', true);
        
        return $state;
    }
    
    public function adjustPriorities(array $agents, GraphState $state): array
    {
        // 基础优先级调整：按名称排序
        usort($agents, function($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });
        
        return $agents;
    }
}