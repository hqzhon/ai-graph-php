#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Agent\Example\AdvancedCollaborativeWorkflow;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\UnifiedGraph\State\State;

echo "=== Multi-Agent Workflow Test ===\n\n";

// 检查是否提供了API密钥
$deepseekKey = getenv('DEEPSEEK_API_KEY');
$qwenKey = getenv('QWEN_API_KEY');

if (empty($deepseekKey) && empty($qwenKey)) {
    echo "Warning: No API keys found in environment variables.\n";
    echo "Please set DEEPSEEK_API_KEY and/or QWEN_API_KEY environment variables.\n\n";
    
    // 使用假的配置数据进行测试
    $configData = [
        'deepseek_api_key' => 'fake-key-for-testing',
        'qwen_api_key' => 'fake-key-for-testing'
    ];
} else {
    $configData = [];
    if (!empty($deepseekKey)) {
        $configData['deepseek_api_key'] = $deepseekKey;
    }
    if (!empty($qwenKey)) {
        $configData['qwen_api_key'] = $qwenKey;
    }
}

try {
    echo "Creating workflow...\n";
    
    // 创建工作流
    $graph = AdvancedCollaborativeWorkflow::create($configData);
    
    echo "Compiling workflow...\n";
    
    // 编译工作流
    $compiled = $graph->compile();
    
    echo "Creating initial state...\n";
    
    // 创建初始状态
    $initialState = new State([
        'task' => 'Research ways to improve energy efficiency in data centers',
        'user_input' => 'Research ways to improve energy efficiency in data centers'
    ]);
    
    echo "Executing workflow...\n";
    
    // 执行工作流
    $finalState = $compiled->execute($initialState);
    
    echo "Workflow execution completed.\n\n";
    
    echo "Final State:\n";
    print_r($finalState->getData());
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
    
    // 显示堆栈跟踪（仅在开发环境中）
    if (getenv('APP_ENV') === 'development') {
        echo "Stack trace:\n";
        echo $e->getTraceAsString() . "\n";
    }
}

echo "\n=== Test Complete ===\n";