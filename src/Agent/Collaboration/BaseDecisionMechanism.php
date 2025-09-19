<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

abstract class BaseDecisionMechanism implements DecisionMechanismInterface
{
    protected $name;
    
    public function __construct(string $name = "BaseDecisionMechanism")
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * 做出决策
     * 
     * @param State $state 当前状态
     * @return State 决策后的状态
     */
    abstract public function makeDecision(State $state): State;
}