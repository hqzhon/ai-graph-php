<?php

namespace App\Agent;

use App\UnifiedGraph\State\State;

class ResponseAgent extends BaseAgent
{
    private $response;
    
    public function __construct(string $name, string $response, string $description = '')
    {
        parent::__construct($name);
        $this->response = $response;
    }
    
    public function execute(string $task, ?State $context = null): State
    {
        // 获取上下文信息
        $contextData = $this->getContext($context);
        
        // 创建新的状态
        $newState = new State($contextData);
        
        // 设置响应
        $newState->set('response', $this->response);
        $newState->set('agent_response', $this->response);
        $newState->set('agent_' . $this->name . '_response', $this->response);
        
        // 更新记忆
        $this->updateMemory([
            'last_task' => $task,
            'last_response' => $this->response
        ]);
        
        return $newState;
    }
}