<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

interface CoordinatorInterface
{
    /**
     * 协调智能体间的协作
     * 
     * @param State $state 当前状态
     * @return State 协调后的状态
     */
    public function coordinate(State $state): State;
}