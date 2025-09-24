<?php

namespace UnifiedGraph\Example;

use UnifiedGraph\StateGraph;
use UnifiedGraph\State\State;
use UnifiedGraph\State\ChannelsState;
use UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

class AdvancedWorkflowExample
{
    public static function createGraph()
    {
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
            ]
        ]);
        
        // 添加节点
        $graph->addNode('start', function ($state) {
            return [
                'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
                'step' => 'start',
                'counter' => 1
            ];
        });
        
        $graph->addNode('process', function ($state) {
            $messages = $state['messages'] ?? [];
            $messages[] = ['role' => 'user', 'content' => 'Hello, what is the weather today?'];
            return [
                'messages' => $messages,
                'step' => 'process',
                'counter' => 1
            ];
        });
        
        $graph->addNode('end', function ($state) {
            $messages = $state['messages'] ?? [];
            $messages[] = ['role' => 'assistant', 'content' => 'The weather is sunny today.'];
            return [
                'messages' => $messages,
                'step' => 'end',
                'counter' => 1
            ];
        });
        
        // 添加边
        $graph->addEdge('start', 'process');
        $graph->addEdge('process', 'end');
        
        // 设置起始和结束点
        $graph->setEntryPoint('start');
        $graph->setFinishPoint('end');
        
        // 设置检查点保存器
        $checkpointSaver = new MemoryCheckpointSaver();
        $graph->setCheckpointSaver($checkpointSaver);
        
        return $graph;
    }
    
    public static function run()
    {
        // 创建图
        $graph = self::createGraph();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'advanced_example'
        ]);
        
        // 执行图
        try {
            $finalState = $compiled->execute($initialState);
            return $finalState;
        } catch (\RuntimeException $e) {
            // 处理中断情况
            echo "Workflow interrupted: " . $e->getMessage() . "\n";
            return $initialState;
        }
    }
    
    public static function runWithInterruption()
    {
        // 创建图
        $graph = self::createGraph();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'advanced_example'
        ]);
        
        // 执行图，但在'process'节点后中断
        try {
            $threadId = 'example_thread_1';
            foreach ($compiled->stream($initialState, $threadId, [], ['process']) as $state) {
                echo "Step: " . ($state->get('step') ?? 'unknown') . "\n";
            }
        } catch (\RuntimeException $e) {
            // 处理中断情况
            echo "Workflow interrupted: " . $e->getMessage() . "\n";
            return $initialState;
        }
    }
}