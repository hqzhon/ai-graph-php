<?php

namespace App\Agent;

use App\LangGraph\State\GraphState;

abstract class BaseAgent implements AgentInterface
{
    protected $name;
    protected $description;
    
    public function __construct(string $name, string $description = '')
    {
        $this->name = $name;
        $this->description = $description;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function act(GraphState $state): GraphState
    {
        // 执行前钩子
        $state = $this->beforeAct($state);
        
        // 执行智能体逻辑
        $state = $this->process($state);
        
        // 执行后钩子
        $state = $this->afterAct($state);
        
        return $state;
    }
    
    /**
     * 智能体执行前的钩子方法
     * 
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    protected function beforeAct(GraphState $state): GraphState
    {
        // 记录智能体开始执行
        $state->set('agent_' . $this->name . '_start_time', microtime(true));
        return $state;
    }
    
    /**
     * 智能体执行后的钩子方法
     * 
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    protected function afterAct(GraphState $state): GraphState
    {
        // 记录智能体执行结束
        $state->set('agent_' . $this->name . '_end_time', microtime(true));
        return $state;
    }
    
    /**
     * 智能体的核心处理逻辑，子类需要实现此方法
     * 
     * @param GraphState $state 当前状态
     * @return GraphState 更新后的状态
     */
    abstract protected function process(GraphState $state): GraphState;
}