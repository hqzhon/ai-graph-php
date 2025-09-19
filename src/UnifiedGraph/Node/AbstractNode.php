<?php

namespace App\UnifiedGraph\Node;

abstract class AbstractNode implements NodeInterface
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
    
    /**
     * 节点执行前的钩子方法
     * 
     * @param array $state 当前状态
     * @return array 更新后的状态
     */
    protected function beforeExecute(array $state): array
    {
        return $state;
    }
    
    /**
     * 节点执行后的钩子方法
     * 
     * @param array $state 当前状态
     * @return array 更新后的状态
     */
    protected function afterExecute(array $state): array
    {
        return $state;
    }
    
    public function execute(array $state): array
    {
        // 执行前钩子
        $state = $this->beforeExecute($state);
        
        // 执行节点逻辑
        $state = $this->process($state);
        
        // 执行后钩子
        $state = $this->afterExecute($state);
        
        return $state;
    }
    
    /**
     * 节点的核心处理逻辑，子类需要实现此方法
     * 
     * @param array $state 当前状态
     * @return array 更新后的状态
     */
    abstract protected function process(array $state): array;
}