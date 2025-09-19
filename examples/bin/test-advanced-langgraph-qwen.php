#!/usr/bin/env php
<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\State\State;

echo "=== Advanced LangGraph with Qwen API Integration Test ===\n\n";

// 创建配置
$configFile = __DIR__ . '/../../config/model.php';
if (file_exists($configFile)) {
    $config = ModelConfig::fromFile($configFile);
} else {
    $config = new ModelConfig();
}

// 创建工厂
$factory = new ModelFactory($config->all());

// 检查Qwen API密钥是否设置
$qwenApiKey = $config->get('qwen_api_key');

if (!$qwenApiKey) {
    echo "Warning: Qwen API key not found. Please set QWEN_API_KEY environment variable\n";
    echo "or create a config/model.php file with your API key.\n\n";
    exit(1);
}

echo "Qwen API key found. Proceeding with integration test...\n\n";

// 创建一个使用Qwen API的工作流
echo "1. Creating a workflow with Qwen API integration...\n";

// 创建状态图
$graph = new StateGraph(ChannelsState::class);

// 添加通道定义
$graph->addChannels([
    'messages' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            if (is_array($a) && is_array($b)) {
                return array_merge($a, $b);
            }
            return $b;
        },
        'default' => []
    ],
    'step' => [
        'type' => 'last_value',
        'default' => 'start'
    ],
    'response' => [
        'type' => 'last_value',
        'default' => ''
    ]
]);

// 添加节点
$graph->addNode('start', function ($state) {
    return [
        'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
        'step' => 'start'
    ];
});

// 添加使用Qwen API的节点
$graph->addNode('qwen_query', function ($state) use ($factory) {
    $messages = $state['messages'] ?? [];
    $messages[] = ['role' => 'user', 'content' => 'What is the capital of France?'];
    
    try {
        $client = $factory->createClient('qwen');
        $response = $client->chatComplete($messages);
        
        return [
            'messages' => $messages,
            'step' => 'qwen_query',
            'response' => $response
        ];
    } catch (Exception $e) {
        return [
            'messages' => $messages,
            'step' => 'qwen_query',
            'response' => 'Error: ' . $e->getMessage()
        ];
    }
});

$graph->addNode('end', function ($state) {
    $messages = $state['messages'] ?? [];
    $response = $state['response'] ?? 'No response';
    $messages[] = ['role' => 'assistant', 'content' => $response];
    
    return [
        'messages' => $messages,
        'step' => 'end',
        'response' => $response
    ];
});

// 添加边
$graph->addEdge('start', 'qwen_query');
$graph->addEdge('qwen_query', 'end');

// 设置起始和结束点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 编译图
$compiled = $graph->compile();

// 创建初始状态
$initialState = new State([
    'workflow' => 'qwen_integration_test'
]);

echo "2. Executing workflow with Qwen API call...\n";

// 执行图
$finalState = $compiled->execute($initialState);

// 显示结果
$data = $finalState->getData();
echo "Final State:\n";
echo "- Step: " . ($data['step'] ?? 'unknown') . "\n";
echo "- Response: " . ($data['response'] ?? 'no response') . "\n";
echo "- Message count: " . count($data['messages'] ?? []) . "\n";

echo "\n=== Integration Test Complete ===\n";