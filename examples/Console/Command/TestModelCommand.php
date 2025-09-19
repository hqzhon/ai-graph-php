<?php

namespace App\Examples\Console\Command;

use App\Examples\Console\Command as BaseCommand;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

class TestModelCommand extends BaseCommand
{
    private $modelFactory;
    private $modelConfig;

    public function __construct(ModelFactory $modelFactory, ModelConfig $modelConfig)
    {
        parent::__construct('model:test', 'Test model API calls');
        $this->modelFactory = $modelFactory;
        $this->modelConfig = $modelConfig;
    }
    
    public function execute($args)
    {
        echo "Testing model API calls...\n\n";
        
        // 检查API密钥是否设置
        $deepseekApiKey = $this->modelConfig->get('deepseek_api_key');
        $qwenApiKey = $this->modelConfig->get('qwen_api_key');
        
        if (!$deepseekApiKey && !$qwenApiKey) {
            echo "Warning: No API keys found. Please set DEEPSEEK_API_KEY and/or QWEN_API_KEY environment variables\n";
            echo "or create a config/model.php file with your API keys.\n\n";
            
            echo "Supported models: " . implode(', ', $this->modelFactory->getSupportedModels()) . "\n";
            return;
        }
        
        // 定义测试消息
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'What is the capital of France?']
        ];
        
        // 测试DeepSeek模型（如果配置了API密钥）
        if ($deepseekApiKey) {
            try {
                echo "1. Testing DeepSeek model...\n";
                $client = $this->modelFactory->createClient('deepseek');
                
                $response = $client->chatComplete($messages);
                echo "DeepSeek Response: " . $response . "\n\n";
            } catch (\Exception $e) {
                echo "DeepSeek Error: " . $e->getMessage() . "\n\n";
            }
        }
        
        // 测试Qwen模型（如果配置了API密钥）
        if ($qwenApiKey) {
            try {
                echo "2. Testing Qwen model...\n";
                $client = $this->modelFactory->createClient('qwen');
                
                $response = $client->chatComplete($messages);
                echo "Qwen Response: " . $response . "\n\n";
            } catch (\Exception $e) {
                echo "Qwen Error: " . $e->getMessage() . "\n\n";
            }
        }
        
        echo "Model testing complete.\n";
    }
}