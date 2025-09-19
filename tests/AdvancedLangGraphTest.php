<?php

namespace App\Tests\UnifiedGraph;

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\Checkpoint\CheckpointSaverInterface;
use App\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;
use App\UnifiedGraph\Exception\LangGraphException;
use App\UnifiedGraph\Exception\InterruptedException;
use PHPUnit\Framework\TestCase;

class AdvancedLangGraphTest extends TestCase
{
    public function testChannelsState()
    {
        // 创建通道定义
        $channels = [
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
        ];
        
        // 创建带通道的状态
        $state = new ChannelsState($channels, [
            'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
            'step' => 'initialized',
            'counter' => 0
        ]);
        
        // 验证初始状态
        $this->assertEquals('initialized', $state->get('step'));
        $this->assertEquals(0, $state->get('counter'));
        $this->assertCount(1, $state->get('messages'));
        
        // 测试合并操作
        $state->merge([
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'counter' => 1
        ]);
        
        // 验证合并后的状态
        $this->assertEquals('initialized', $state->get('step'));
        $this->assertEquals(1, $state->get('counter'));
        $this->assertCount(2, $state->get('messages'));
        
        // 测试设置操作
        $state->set('step', 'processing');
        
        // 验证设置后的状态
        $this->assertEquals('processing', $state->get('step'));
    }
    
    public function testGraphWithChannels()
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
            ]
        ]);
        
        // 添加节点
        $graph->addNode('start', function ($state) {
            return [
                'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
                'step' => 'start'
            ];
        });
        
        $graph->addNode('process', function ($state) {
            $messages = $state['messages'] ?? [];
            $messages[] = ['role' => 'user', 'content' => 'Hello, what is the weather today?'];
            return [
                'messages' => $messages,
                'step' => 'process'
            ];
        });
        
        $graph->addNode('end', function ($state) {
            $messages = $state['messages'] ?? [];
            $messages[] = ['role' => 'assistant', 'content' => 'The weather is sunny today.'];
            return [
                'messages' => $messages,
                'step' => 'end'
            ];
        });
        
        // 添加边
        $graph->addEdge('start', 'process');
        $graph->addEdge('process', 'end');
        
        // 设置起始和结束点
        $graph->setEntryPoint('start');
        $graph->setFinishPoint('end');
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'test_channels'
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        // 验证最终状态
        $data = $finalState->getData();
        $this->assertEquals('end', $data['step']);
        // 验证messages数组不为空
        $this->assertNotEmpty($data['messages']);
    }
    
    public function testInterruptBefore()
    {
        // 创建状态图
        $graph = new StateGraph(ChannelsState::class);
        
        // 添加通道定义
        $graph->addChannels([
            'step' => [
                'type' => 'last_value',
                'default' => 'start'
            ]
        ]);
        
        // 添加节点
        $graph->addNode('start', function ($state) {
            return ['step' => 'start'];
        });
        
        $graph->addNode('process', function ($state) {
            return ['step' => 'process'];
        });
        
        $graph->addNode('end', function ($state) {
            return ['step' => 'end'];
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
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'test_interrupt'
        ]);
        
        // 执行图，在'process'节点前中断
        $this->expectException(InterruptedException::class);
        
        $threadId = 'test_thread_1';
        $finalState = $compiled->execute($initialState, $threadId, ['process'], []);
    }
    
    public function testInterruptAfter()
    {
        // 创建状态图
        $graph = new StateGraph(ChannelsState::class);
        
        // 添加通道定义
        $graph->addChannels([
            'step' => [
                'type' => 'last_value',
                'default' => 'start'
            ]
        ]);
        
        // 添加节点
        $graph->addNode('start', function ($state) {
            return ['step' => 'start'];
        });
        
        $graph->addNode('process', function ($state) {
            return ['step' => 'process'];
        });
        
        $graph->addNode('end', function ($state) {
            return ['step' => 'end'];
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
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'test_interrupt'
        ]);
        
        // 执行图，在'process'节点后中断
        $this->expectException(InterruptedException::class);
        
        $threadId = 'test_thread_2';
        $finalState = $compiled->execute($initialState, $threadId, [], ['process']);
    }
    
    public function testStateTracking()
    {
        // 创建通道定义
        $channels = [
            'step' => [
                'type' => 'last_value',
                'default' => 'start'
            ]
        ];
        
        // 创建带通道的状态
        $state = new ChannelsState($channels, ['step' => 'initialized']);
        
        // 修改状态
        $state->set('step', 'processing');
        $state->merge(['step' => 'completed']);
        
        // 获取历史记录
        $history = $state->getHistory();
        
        // 验证历史记录
        $this->assertCount(2, $history);
        $this->assertEquals('set_step', $history[0]['node']);
        $this->assertEquals('merge', $history[1]['node']);
    }
}