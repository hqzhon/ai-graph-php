<?php

namespace App\Agent;

use App\LangGraph\State\GraphState;
use App\Model\Factory\ModelFactory;

class ModelBasedAgent extends BaseAgent
{
    private $modelFactory;
    private $modelType;
    private $systemPrompt;
    
    public function __construct(
        string $name, 
        ModelFactory $modelFactory, 
        string $modelType, 
        string $systemPrompt = 'You are a helpful assistant.',
        string $description = ''
    ) {
        parent::__construct($name, $description);
        $this->modelFactory = $modelFactory;
        $this->modelType = $modelType;
        $this->systemPrompt = $systemPrompt;
    }
    
    protected function process(GraphState $state): GraphState
    {
        // 获取用户输入
        $userInput = $state->get('user_input', '');
        
        if (empty($userInput)) {
            $state->set('agent_' . $this->name . '_response', 'No input provided.');
            return $state;
        }
        
        try {
            // 创建模型客户端
            $client = $this->modelFactory->createClient($this->modelType);
            
            // 构造消息
            $messages = [
                ['role' => 'system', 'content' => $this->systemPrompt],
                ['role' => 'user', 'content' => $userInput]
            ];
            
            // 发送请求并获取响应
            $response = $client->chatComplete($messages);
            
            // 设置响应
            $state->set('agent_' . $this->name . '_response', $response);
            $state->set('agent_response', $response);
        } catch (\Exception $e) {
            $state->set('agent_' . $this->name . '_error', $e->getMessage());
            $state->set('agent_' . $this->name . '_response', 'Sorry, I encountered an error: ' . $e->getMessage());
        }
        
        return $state;
    }
}