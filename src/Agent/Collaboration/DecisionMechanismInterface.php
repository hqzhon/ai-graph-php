<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

interface DecisionMechanismInterface
{
    /**
     * 做出决策
     * 
     * @param State $state 当前状态
     * @return State 决策后的状态
     */
    public function makeDecision(State $state): State;
}