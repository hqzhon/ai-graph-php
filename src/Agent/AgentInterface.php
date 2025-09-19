<?php

namespace App\Agent;

use App\UnifiedGraph\State\State;

interface AgentInterface
{
    /**
     * 获取智能体名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 智能体执行任务
     * 
     * @param string $task 任务描述
     * @param State|null $context 上下文状态
     * @return State 执行结果状态
     */
    public function execute(string $task, ?State $context = null): State;
    
    /**
     * 设置智能体的记忆
     * 
     * @param array $memory 记忆数据
     * @return void
     */
    public function setMemory(array $memory): void;
    
    /**
     * 获取智能体的记忆
     * 
     * @return array
     */
    public function getMemory(): array;
}