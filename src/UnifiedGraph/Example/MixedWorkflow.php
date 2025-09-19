<?php

namespace App\UnifiedGraph\Example;

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\Node\AbstractNode;
use App\UnifiedGraph\State\State;

// 自定义节点类
class CustomProcessNode extends AbstractNode
{
    protected function process(array $state): array
    {
        $state['step'] = 'process_node';
        $state['message'] = 'Processed by CustomProcessNode class';
        $state['timestamp'] = date('Y-m-d H:i:s');
        
        return $state;
    }
}

// 混合节点示例：结合 callable 和 Node 对象
class MixedWorkflow
{
    public static function create(): StateGraph
    {
        $graph = new StateGraph(State::class);
        
        // 添加 callable 节点（LangGraph 风格）
        $graph->addNode('start', function ($state) {
            $counter = $state['counter'] ?? 0;
            return [
                'step' => 'start',
                'message' => 'Started with callable node',
                'counter' => $counter
            ];
        });
        
        // 添加对象节点（Graph 风格）
        $graph->addNode('process', new CustomProcessNode('process'));
        
        // 再添加一个 callable 节点
        $graph->addNode('decide', function ($state) {
            $counter = $state['counter'] ?? 0;
            $decision = $counter < 2 ? 'continue' : 'finish';
            
            return [
                'step' => 'decide',
                'message' => 'Decided to ' . $decision,
                'decision' => $decision,
                'counter' => $counter + 1
            ];
        });
        
        // 结束节点
        $graph->addNode('end', function ($state) {
            return [
                'step' => 'end',
                'message' => 'Workflow completed with mixed node types',
                'completed' => true
            ];
        });
        
        // 添加边
        $graph->addEdge('start', 'process');
        $graph->addEdge('process', 'decide');
        
        // 添加条件边
        $graph->addConditionalEdges('decide', function ($state) {
            return $state['decision'] ?? 'finish';
        }, [
            'continue' => 'start',  // 循环回到开始
            'finish' => 'end'
        ]);
        
        // 设置起始和结束节点
        $graph->setEntryPoint('start');
        $graph->setFinishPoint('end');
        
        return $graph;
    }
    
    public static function run(): State
    {
        // 创建图
        $graph = self::create();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'workflow' => 'mixed_example'
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}