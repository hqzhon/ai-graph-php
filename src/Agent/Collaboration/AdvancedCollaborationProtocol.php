<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;
use App\Agent\AgentInterface;

class AdvancedCollaborationProtocol extends BaseCollaborationProtocol
{
    protected $coordinator;
    protected $decisionMechanism;
    protected $swarmIntelligence;
    protected $taskAllocator;
    protected $agents = [];
    
    public function __construct(
        CoordinatorInterface $coordinator,
        DecisionMechanismInterface $decisionMechanism,
        SwarmIntelligenceInterface $swarmIntelligence,
        TaskAllocatorInterface $taskAllocator,
        array $agents = []
    ) {
        parent::__construct("AdvancedCollaborationProtocol");
        $this->coordinator = $coordinator;
        $this->decisionMechanism = $decisionMechanism;
        $this->swarmIntelligence = $swarmIntelligence;
        $this->taskAllocator = $taskAllocator;
        $this->agents = $agents;
    }
    
    public function initialize(string $task): State
    {
        $state = $this->initializeState($task);
        
        // 初始化智能体列表
        $agentNames = [];
        foreach ($this->agents as $agent) {
            if ($agent instanceof AgentInterface) {
                $agentNames[] = $agent->getName();
            }
        }
        
        $state->merge([
            'agents' => $agentNames,
            'phase' => 'initialized'
        ]);
        
        return $state;
    }
    
    public function executeStep(State $state): State
    {
        // 应用群体智能
        $state = $this->swarmIntelligence->applySwarmIntelligence($state);
        
        // 分配任务
        $state = $this->taskAllocator->allocateTasks($state);
        
        // 协调智能体
        $state = $this->coordinator->coordinate($state);
        
        // 做出决策
        $state = $this->decisionMechanism->makeDecision($state);
        
        // 更新阶段
        $state->merge(['phase' => 'step_completed']);
        
        return $state;
    }
    
    public function isComplete(State $state): bool
    {
        $context = $state->getData();
        return isset($context['completed']) && $context['completed'] === true;
    }
}