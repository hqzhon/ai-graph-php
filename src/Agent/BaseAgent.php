<?php

namespace App\Agent;

use App\UnifiedGraph\State\State;

abstract class BaseAgent implements AgentInterface
{
    protected $name;
    protected $memory = [];
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setMemory(array $memory): void
    {
        $this->memory = $memory;
    }
    
    public function getMemory(): array
    {
        return $this->memory;
    }
    
    /**
     * 更新记忆
     * 
     * @param array $updates 要更新的记忆数据
     * @return void
     */
    protected function updateMemory(array $updates): void
    {
        $this->memory = array_merge($this->memory, $updates);
    }
    
    /**
     * 获取上下文信息
     * 
     * @param State|null $context 上下文状态
     * @return array 上下文信息
     */
    protected function getContext(?State $context = null): array
    {
        if ($context === null) {
            return [];
        }
        
        return $context->getData();
    }
}