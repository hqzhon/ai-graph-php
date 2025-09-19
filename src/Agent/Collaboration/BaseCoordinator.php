<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

abstract class BaseCoordinator implements CoordinatorInterface
{
    protected $name;
    
    public function __construct(string $name = "BaseCoordinator")
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * 协调智能体间的协作
     * 
     * @param State $state 当前状态
     * @return State 协调后的状态
     */
    abstract public function coordinate(State $state): State;
}