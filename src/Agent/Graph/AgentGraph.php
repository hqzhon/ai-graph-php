<?php

namespace App\Agent\Graph;

use App\LangGraph\StateGraph;
use App\Agent\AgentInterface;

class AgentGraph extends StateGraph
{
    private $agents = [];
    
    public function addAgent(string $key, AgentInterface $agent): self
    {
        $this->agents[$key] = $agent;
        
        // 将智能体包装为节点
        $agentNode = new AgentNode($agent);
        $this->addNode($key, $agentNode);
        
        return $this;
    }
    
    public function getAgent(string $key): ?AgentInterface
    {
        return $this->agents[$key] ?? null;
    }
    
    public function getAllAgents(): array
    {
        return $this->agents;
    }
}