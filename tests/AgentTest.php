<?php

namespace App\Tests\Agent;

use App\Agent\ResponseAgent;
use App\Agent\AgentFactory;
use App\Agent\Tool\ToolManager;
use App\Agent\Tool\CalculatorTool;
use App\Agent\Memory\ShortTermMemory;
use App\Agent\Memory\ContextManager;
use App\Agent\Communication\Message;
use App\Agent\Communication\MessageQueue;
use App\Agent\ErrorHandling\RetryMechanism;
use PHPUnit\Framework\TestCase;

class AgentTest extends TestCase
{
    public function testResponseAgent()
    {
        $agent = new ResponseAgent('test_agent', 'Hello, World!', 'A test agent');
        
        $this->assertEquals('test_agent', $agent->getName());
        $this->assertEquals('A test agent', $agent->getDescription());
        
        // 创建初始状态
        $state = new \App\LangGraph\State\GraphState();
        
        // 执行智能体
        $updatedState = $agent->act($state);
        
        $this->assertEquals('Hello, World!', $updatedState->get('agent_test_agent_response'));
        $this->assertEquals('Hello, World!', $updatedState->get('agent_response'));
    }
    
    public function testAgentFactory()
    {
        // 创建模型工厂和代理工厂
        $modelConfig = new \App\Model\Config\ModelConfig();
        $modelFactory = new \App\Model\Factory\ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建响应智能体
        $responseAgent = $agentFactory->createResponseAgent('response_agent', 'Test response');
        $this->assertInstanceOf(\App\Agent\ResponseAgent::class, $responseAgent);
        
        // 创建模型基础智能体
        $modelAgent = $agentFactory->createModelBasedAgent('model_agent', 'deepseek', 'Test prompt');
        $this->assertInstanceOf(\App\Agent\ModelBasedAgent::class, $modelAgent);
    }
    
    public function testToolSystem()
    {
        $toolManager = new ToolManager();
        $calculatorTool = new CalculatorTool();
        
        // 注册工具
        $toolManager->register($calculatorTool);
        
        // 验证工具注册
        $this->assertArrayHasKey('calculator', $toolManager->getAll());
        
        // 执行工具
        $result = $toolManager->execute('calculator', [
            'operation' => 'add',
            'a' => 10,
            'b' => 5
        ]);
        
        $this->assertEquals(15, $result);
    }
    
    public function testMemorySystem()
    {
        $memory = new ShortTermMemory();
        
        // 添加记忆
        $memory->add('key1', 'value1');
        
        // 验证记忆
        $this->assertTrue($memory->has('key1'));
        $this->assertEquals('value1', $memory->get('key1'));
        
        // 删除记忆
        $memory->remove('key1');
        $this->assertFalse($memory->has('key1'));
    }
    
    public function testContextManager()
    {
        $contextManager = new ContextManager();
        
        // 添加对话历史
        $contextManager->addHistory('user', 'Hello');
        $contextManager->addHistory('assistant', 'Hi there!');
        
        // 获取对话历史
        $history = $contextManager->getHistory(5);
        $this->assertCount(2, $history);
        
        // 验证历史内容
        $this->assertEquals('user', $history[0]['role']);
        $this->assertEquals('Hello', $history[0]['content']);
        $this->assertEquals('assistant', $history[1]['role']);
        $this->assertEquals('Hi there!', $history[1]['content']);
    }
    
    public function testCommunicationSystem()
    {
        $messageQueue = new MessageQueue();
        
        // 创建消息
        $message = new Message('sender1', 'recipient1', 'Hello, recipient1!');
        
        // 发送消息
        $messageQueue->send($message);
        
        // 接收消息
        $receivedMessage = $messageQueue->receive('recipient1');
        
        $this->assertNotNull($receivedMessage);
        $this->assertEquals('sender1', $receivedMessage->getSender());
        $this->assertEquals('recipient1', $receivedMessage->getRecipient());
        $this->assertEquals('Hello, recipient1!', $receivedMessage->getContent());
    }
    
    public function testRetryMechanism()
    {
        $retryMechanism = new RetryMechanism(3, 10); // 3次重试，10毫秒延迟
        
        // 创建一个会失败两次然后成功的函数
        $attempts = 0;
        $testFunction = function () use (&$attempts) {
            $attempts++;
            if ($attempts < 3) {
                throw new \Exception("Failed attempt $attempts");
            }
            return "Success on attempt $attempts";
        };
        
        // 执行带重试的函数
        $result = $retryMechanism->execute($testFunction);
        
        $this->assertEquals("Success on attempt 3", $result);
        $this->assertEquals(3, $attempts);
    }
}