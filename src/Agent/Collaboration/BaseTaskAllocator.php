<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

abstract class BaseTaskAllocator implements TaskAllocatorInterface
{
    protected $name;
    
    public function __construct(string $name = "BaseTaskAllocator")
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * 分配任务给智能体
     * 
     * @param State $state 当前状态
     * @return State 任务分配后的状态
     */
    abstract public function allocateTasks(State $state): State;
}