<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

interface TaskAllocatorInterface
{
    /**
     * 分配任务给智能体
     * 
     * @param State $state 当前状态
     * @return State 任务分配后的状态
     */
    public function allocateTasks(State $state): State;
}