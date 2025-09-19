#!/usr/bin/env php
<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\State\State;
use App\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;
use App\UnifiedGraph\Exception\InterruptedException;

echo "=== Comprehensive LangGraph Integration Test ===\n\n";

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
$hasQwen = !empty($qwenApiKey);

echo "Available features:\n";
echo "- Channel-based state management: YES\n";
echo "- State tracking: YES\n";
echo "- Checkpoint and persistence: YES\n";
echo "- Interruption and resumption: YES\n";
echo "- Qwen API integration: " . ($hasQwen ? "YES" : "NO") . "\n\n";

// 1. 测试通道机制和状态追踪
echo "1. Testing Channel-based State Management and Tracking...\n";

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
    'counter' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            return $a + $b;
        },
        'default' => 0
    ],
    'result' => [
        'type' => 'last_value',
        'default' => ''
    ]
]);

// 添加节点
$graph->addNode('start', function ($state) {
    echo "  Executing 'start' node...\n";
    return [
        'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
        'step' => 'start',
        'counter' => 1
    ];
});

$graph->addNode('process', function ($state) {
    echo "  Executing 'process' node...\n";
    $messages = $state['messages'] ?? [];
    $messages[] = ['role' => 'user', 'content' => 'Count to 5'];
    
    return [
        'messages' => $messages,
        'step' => 'process',
        'counter' => 1
    ];
});

// 如果有Qwen API密钥，添加API调用节点
if ($hasQwen) {
    $graph->addNode('api_call', function ($state) use ($factory) {
        echo "  Executing 'api_call' node (Qwen API)...\n";
        $messages = $state['messages'] ?? [];
        $messages[] = ['role' => 'user', 'content' => 'What is 2+2?'];
        
        try {
            $client = $factory->createClient('qwen');
            $response = $client->chatComplete($messages);
            
            return [
                'messages' => $messages,
                'step' => 'api_call',
                'counter' => 1,
                'result' => $response
            ];
        } catch (Exception $e) {
            return [
                'messages' => $messages,
                'step' => 'api_call',
                'counter' => 1,
                'result' => 'Error: ' . $e->getMessage()
            ];
        }
    });
}

$graph->addNode('end', function ($state) {
    echo "  Executing 'end' node...\n";
    $messages = $state['messages'] ?? [];
    $result = $state['result'] ?? 'No result';
    $messages[] = ['role' => 'assistant', 'content' => $result];
    
    return [
        'messages' => $messages,
        'step' => 'end',
        'counter' => 1,
        'result' => $result
    ];
});

// 添加边
$graph->addEdge('start', 'process');
if ($hasQwen) {
    $graph->addEdge('process', 'api_call');
    $graph->addEdge('api_call', 'end');
} else {
    $graph->addEdge('process', 'end');
}

// 设置起始和结束点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 2. 测试检查点功能
echo "\n2. Testing Checkpoint and Persistence...\n";
$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

// 编译图
$compiled = $graph->compile();

// 创建初始状态
$initialState = new State([
    'workflow' => 'comprehensive_test'
]);

// 3. 执行图并显示状态追踪
echo "\n3. Executing workflow with State Tracking...\n";
$finalState = $compiled->execute($initialState);

// 显示最终状态
$data = $finalState->getData();
echo "\nFinal State:\n";
echo "- Step: " . ($data['step'] ?? 'unknown') . "\n";
echo "- Counter: " . ($data['counter'] ?? 0) . "\n";
echo "- Message count: " . count($data['messages'] ?? []) . "\n";
if (isset($data['result'])) {
    echo "- Result: " . substr($data['result'], 0, 50) . (strlen($data['result']) > 50 ? '...' : '') . "\n";
}

// 显示状态变更历史
if ($finalState instanceof ChannelsState) {
    $history = $finalState->getHistory();
    echo "- State change history: " . count($history) . " changes\n";
}

// 4. 测试中断和恢复功能
echo "\n4. Testing Interruption and Resumption...\n";
$interruptGraph = new StateGraph(ChannelsState::class);
$interruptGraph->addChannels([
    'step' => [
        'type' => 'last_value',
        'default' => 'start'
    ]
]);

$interruptGraph->addNode('start', function ($state) {
    return ['step' => 'start'];
});

$interruptGraph->addNode('interrupt_point', function ($state) {
    return ['step' => 'interrupt_point'];
});

$interruptGraph->addNode('resume_point', function ($state) {
    return ['step' => 'resume_point'];
});

$interruptGraph->addNode('end', function ($state) {
    return ['step' => 'end'];
});

$interruptGraph->addEdge('start', 'interrupt_point');
$interruptGraph->addEdge('interrupt_point', 'resume_point');
$interruptGraph->addEdge('resume_point', 'end');

$interruptGraph->setEntryPoint('start');
$interruptGraph->setFinishPoint('end');

$interruptCheckpointSaver = new MemoryCheckpointSaver();
$interruptGraph->setCheckpointSaver($interruptCheckpointSaver);

$interruptCompiled = $interruptGraph->compile();

$interruptInitialState = new State([
    'workflow' => 'interruption_test'
]);

$threadId = 'test_thread_' . uniqid();

try {
    echo "  Attempting to execute with interruption after 'interrupt_point'...\n";
    $interruptCompiled->execute($interruptInitialState, $threadId, [], ['interrupt_point']);
    echo "  ERROR: Should have been interrupted!\n";
} catch (InterruptedException $e) {
    echo "  Successfully interrupted after node: " . $e->getNodeKey() . "\n";
    
    // 检查是否保存了检查点
    $checkpoints = $interruptCheckpointSaver->list($threadId);
    echo "  Checkpoints saved: " . count($checkpoints) . "\n";
}

echo "\n=== Comprehensive Integration Test Complete ===\n";