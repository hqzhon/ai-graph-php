<?php

namespace App\Agent;

use App\Model\Factory\ModelFactory;

class AgentFactory
{
    private $modelFactory;
    
    public function __construct(ModelFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }
    
    /**
     * 创建响应智能体
     * 
     * @param string $name 智能体名称
     * @param string $response 响应内容
     * @param string $description 智能体描述
     * @return ResponseAgent
     */
    public function createResponseAgent(string $name, string $response, string $description = ''): ResponseAgent
    {
        return new ResponseAgent($name, $response, $description);
    }
    
    /**
     * 创建基于模型的智能体
     * 
     * @param string $name 智能体名称
     * @param string $model 模型名称
     * @param string $systemPrompt 系统提示
     * @param string $description 智能体描述
     * @return ModelBasedAgent
     */
    public function createModelBasedAgent(
        string $name, 
        string $model, 
        string $systemPrompt = 'You are a helpful assistant.', 
        string $description = ''
    ): ModelBasedAgent {
        $modelClient = $this->modelFactory->createClient($model);
        return new ModelBasedAgent($name, $modelClient, $systemPrompt, $description);
    }
}