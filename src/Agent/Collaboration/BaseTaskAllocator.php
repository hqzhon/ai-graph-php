<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class BaseTaskAllocator implements TaskAllocatorInterface
{
    protected $agentLoads = [];
    protected $taskAssignments = [];
    
    public function allocateTask(string $task, array $agents, GraphState $state): ?string
    {
        // 简单的任务分配逻辑：选择负载最低的智能体
        $selectedAgent = null;
        $minLoad = 1.0;
        
        foreach ($agents as $agent) {
            $load = $this->getAgentLoad($agent);
            if ($load < $minLoad) {
                $minLoad = $load;
                $selectedAgent = $agent;
            }
        }
        
        if ($selectedAgent !== null) {
            // 更新任务分配记录
            $this->taskAssignments[] = [
                'task' => $task,
                'agent' => $selectedAgent->getName(),
                'timestamp' => microtime(true)
            ];
            
            // 增加智能体负载
            $this->increaseAgentLoad($selectedAgent);
        }
        
        return $selectedAgent ? $selectedAgent->getName() : null;
    }
    
    public function reallocateTask(string $task, array $agents, GraphState $state): ?string
    {
        // 简单的重新分配逻辑：选择当前未分配任务的智能体
        foreach ($agents as $agent) {
            if (!$this->hasAssignedTasks($agent)) {
                // 更新任务分配记录
                $this->taskAssignments[] = [
                    'task' => $task,
                    'agent' => $agent->getName(),
                    'timestamp' => microtime(true),
                    'reallocated' => true
                ];
                
                // 增加智能体负载
                $this->increaseAgentLoad($agent);
                
                return $agent->getName();
            }
        }
        
        return null;
    }
    
    public function getAgentLoad(AgentInterface $agent): float
    {
        $agentName = $agent->getName();
        return $this->agentLoads[$agentName] ?? 0.0;
    }
    
    /**
     * 增加智能体负载
     * 
     * @param AgentInterface $agent 智能体
     * @return void
     */
    protected function increaseAgentLoad(AgentInterface $agent): void
    {
        $agentName = $agent->getName();
        $currentLoad = $this->agentLoads[$agentName] ?? 0.0;
        $this->agentLoads[$agentName] = min(1.0, $currentLoad + 0.1);
    }
    
    /**
     * 检查智能体是否有分配的任务
     * 
     * @param AgentInterface $agent 智能体
     * @return bool
     */
    protected function hasAssignedTasks(AgentInterface $agent): bool
    {
        $agentName = $agent->getName();
        foreach ($this->taskAssignments as $assignment) {
            if ($assignment['agent'] === $agentName) {
                return true;
            }
        }
        return false;
    }
}