<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

abstract class BaseSwarmIntelligence implements SwarmIntelligenceInterface
{
    protected $name;
    
    public function __construct(string $name = "BaseSwarmIntelligence")
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * 应用群体智能
     * 
     * @param State $state 当前状态
     * @return State 应用群体智能后的状态
     */
    abstract public function applySwarmIntelligence(State $state): State;
}