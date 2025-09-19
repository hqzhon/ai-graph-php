<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

interface SwarmIntelligenceInterface
{
    /**
     * 应用群体智能
     * 
     * @param State $state 当前状态
     * @return State 应用群体智能后的状态
     */
    public function applySwarmIntelligence(State $state): State;
}