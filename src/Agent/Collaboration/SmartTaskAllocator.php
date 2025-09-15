<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class SmartTaskAllocator extends BaseTaskAllocator
{
    public function allocateTask(string $task, array $agents, GraphState $state): ?string
    {
        // 更智能的任务分配逻辑：
        // 1. 分析任务类型
        // 2. 评估智能体能力匹配度
        // 3. 考虑智能体当前负载
        // 4. 选择最佳智能体
        
        $taskType = $this->analyzeTaskType($task);
        $bestAgent = $this->selectBestAgent($taskType, $agents, $state);
        
        if ($bestAgent !== null) {
            // 更新任务分配记录
            $this->taskAssignments[] = [
                'task' => $task,
                'agent' => $bestAgent->getName(),
                'timestamp' => microtime(true),
                'task_type' => $taskType
            ];
            
            // 增加智能体负载
            $this->increaseAgentLoad($bestAgent);
        }
        
        return $bestAgent ? $bestAgent->getName() : null;
    }
    
    /**
     * 分析任务类型
     * 
     * @param string $task 任务描述
     * @return string 任务类型
     */
    protected function analyzeTaskType(string $task): string
    {
        $task = strtolower($task);
        
        if (strpos($task, 'calculate') !== false || strpos($task, 'compute') !== false) {
            return 'calculation';
        }
        
        if (strpos($task, 'analyze') !== false || strpos($task, 'review') !== false) {
            return 'analysis';
        }
        
        if (strpos($task, 'create') !== false || strpos($task, 'generate') !== false) {
            return 'creation';
        }
        
        if (strpos($task, 'research') !== false || strpos($task, 'find') !== false) {
            return 'research';
        }
        
        return 'general';
    }
    
    /**
     * 选择最佳智能体
     * 
     * @param string $taskType 任务类型
     * @param array $agents 可用智能体
     * @param GraphState $state 状态
     * @return AgentInterface|null 最佳智能体
     */
    protected function selectBestAgent(string $taskType, array $agents, GraphState $state): ?AgentInterface
    {
        // 简化的智能体选择逻辑：
        // 1. 优先选择与任务类型匹配的智能体
        // 2. 考虑智能体负载
        // 3. 考虑历史表现
        
        $bestAgent = null;
        $bestScore = -1;
        
        foreach ($agents as $agent) {
            $score = $this->calculateAgentScore($agent, $taskType, $state);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestAgent = $agent;
            }
        }
        
        return $bestAgent;
    }
    
    /**
     * 计算智能体得分
     * 
     * @param AgentInterface $agent 智能体
     * @param string $taskType 任务类型
     * @param GraphState $state 状态
     * @return float 得分
     */
    protected function calculateAgentScore(AgentInterface $agent, string $taskType, GraphState $state): float
    {
        // 简化的得分计算：
        // 1. 类型匹配度 (0-1)
        // 2. 负载因素 (0-1，负载越低得分越高)
        // 3. 历史表现 (0-1)
        
        $typeMatch = $this->getTypeMatchScore($agent, $taskType);
        $loadFactor = 1 - $this->getAgentLoad($agent);
        $historyScore = $this->getHistoryScore($agent);
        
        // 综合得分
        return ($typeMatch * 0.5) + ($loadFactor * 0.3) + ($historyScore * 0.2);
    }
    
    /**
     * 获取类型匹配得分
     * 
     * @param AgentInterface $agent 智能体
     * @param string $taskType 任务类型
     * @return float 匹配得分 (0-1)
     */
    protected function getTypeMatchScore(AgentInterface $agent, string $taskType): float
    {
        // 简化实现：基于智能体名称和任务类型的匹配
        $agentName = strtolower($agent->getName());
        $taskType = strtolower($taskType);
        
        // 如果智能体名称包含任务类型关键词，则认为匹配度高
        if (strpos($agentName, $taskType) !== false) {
            return 1.0;
        }
        
        // 通用智能体
        if (strpos($agentName, 'general') !== false) {
            return 0.8;
        }
        
        return 0.5;
    }
    
    /**
     * 获取历史表现得分
     * 
     * @param AgentInterface $agent 智能体
     * @return float 历史表现得分 (0-1)
     */
    protected function getHistoryScore(AgentInterface $agent): float
    {
        // 简化实现：返回固定值
        // 在实际应用中，会基于智能体的历史任务完成情况计算
        return 0.7;
    }
}