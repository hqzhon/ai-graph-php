<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class BaseCollaborationProtocol implements CollaborationProtocolInterface
{
    protected $agents = [];
    protected $taskHistory = [];
    
    public function initialize(array $agents): void
    {
        $this->agents = $agents;
    }
    
    public function requestCollaboration(AgentInterface $agent, string $request, GraphState $state)
    {
        // 记录协作请求
        $this->logCollaborationRequest($agent->getName(), $request);
        
        // 简单的协作逻辑：广播请求给所有其他智能体
        $responses = [];
        foreach ($this->agents as $otherAgent) {
            if ($otherAgent->getName() !== $agent->getName()) {
                // 模拟其他智能体的响应
                $response = $this->simulateAgentResponse($otherAgent, $request, $state);
                if ($response !== null) {
                    $responses[$otherAgent->getName()] = $response;
                }
            }
        }
        
        return $responses;
    }
    
    public function assignTask(string $task, array $eligibleAgents, GraphState $state): ?string
    {
        // 简单的任务分配逻辑：选择第一个合适的智能体
        if (!empty($eligibleAgents)) {
            $selectedAgent = reset($eligibleAgents);
            $this->taskHistory[] = [
                'task' => $task,
                'assigned_to' => $selectedAgent->getName(),
                'timestamp' => microtime(true)
            ];
            
            return $selectedAgent->getName();
        }
        
        return null;
    }
    
    public function resolveConflict(array $conflict, GraphState $state): bool
    {
        // 简单的冲突解决逻辑：记录冲突并返回true表示已处理
        $state->set('conflict_resolved', true);
        $state->set('conflict_details', $conflict);
        
        return true;
    }
    
    /**
     * 记录协作请求
     * 
     * @param string $agentName 智能体名称
     * @param string $request 请求内容
     * @return void
     */
    protected function logCollaborationRequest(string $agentName, string $request): void
    {
        $state = new GraphState();
        $state->set('collaboration_request', [
            'agent' => $agentName,
            'request' => $request,
            'timestamp' => microtime(true)
        ]);
    }
    
    /**
     * 模拟智能体响应
     * 
     * @param AgentInterface $agent 智能体
     * @param string $request 请求
     * @param GraphState $state 状态
     * @return mixed 响应
     */
    protected function simulateAgentResponse(AgentInterface $agent, string $request, GraphState $state)
    {
        // 简单的响应逻辑
        return "Response from " . $agent->getName() . " to request: " . $request;
    }
}