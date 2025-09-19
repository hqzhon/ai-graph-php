#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

echo "=== Model Client Demo ===\n\n";

// 创建配置
$config = new ModelConfig();

// 创建工厂
$factory = new ModelFactory($config->all());

// 检查API密钥是否设置
$deepseekApiKey = $config->get('deepseek_api_key');
$qwenApiKey = $config->get('qwen_api_key');

if (!$deepseekApiKey && !$qwenApiKey) {
    echo "Warning: No API keys found. Please set DEEPSEEK_API_KEY and/or QWEN_API_KEY environment variables\n";
    echo "or create a config/model.php file with your API keys.\n\n";
    
    echo "Supported models: " . implode(', ', $factory->getSupportedModels()) . "\n";
    exit(0);
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
        $client = $factory->createClient('deepseek');
        
        $response = $client->chatComplete($messages);
        echo "DeepSeek Response: " . $response . "\n\n";
    } catch (Exception $e) {
        echo "DeepSeek Error: " . $e->getMessage() . "\n\n";
    }
}

// 测试Qwen模型（如果配置了API密钥）
if ($qwenApiKey) {
    try {
        echo "2. Testing Qwen model...\n";
        $client = $factory->createClient('qwen');
        
        $response = $client->chatComplete($messages);
        echo "Qwen Response: " . $response . "\n\n";
    } catch (Exception $e) {
        echo "Qwen Error: " . $e->getMessage() . "\n\n";
    }
}

echo "=== Demo Complete ===\n";