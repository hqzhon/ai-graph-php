<?php

namespace App\Agent\Graph;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class AgentNode
{
    private $agent;
    
    public function __construct(AgentInterface $agent)
    {
        $this->agent = $agent;
    }
    
    public function getAgent(): AgentInterface
    {
        return $this->agent;
    }
    
    public function getName(): string
    {
        return $this->agent->getName();
    }
    
    public function __invoke(array $state): array
    {
        $graphState = new GraphState($state);
        $updatedState = $this->agent->act($graphState);
        return $updatedState->getData();
    }
}