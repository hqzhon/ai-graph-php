<?php

namespace App\LangGraph\Node;

abstract class BaseNode implements NodeInterface
{
    protected $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function __invoke(array $state): ?array
    {
        return $this->run($state);
    }
    
    /**
     * 节点的核心执行逻辑
     * 
     * @param array $state 当前状态
     * @return array|null 更新后的状态
     */
    abstract protected function run(array $state): ?array;
}