<?php

namespace App\UnifiedGraph\Example;

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;

class ExampleWorkflow
{
    public static function create(): StateGraph
    {
        $graph = new StateGraph(State::class);
        
        // 添加节点
        $graph->addNode('start', function ($state) {
            return [
                'step' => 'start',
                'message' => 'Workflow started',
                'counter' => 0
            ];
        });
        
        $graph->addNode('process', function ($state) {
            $counter = $state['counter'] ?? 0;
            return [
                'step' => 'process',
                'message' => 'Processing item ' . ($counter + 1),
                'counter' => $counter + 1
            ];
        });
        
        $graph->addNode('decide', function ($state) {
            $counter = $state['counter'] ?? 0;
            $decision = $counter < 3 ? 'continue' : 'finish';
            
            return [
                'step' => 'decide',
                'message' => 'Decided to ' . $decision,
                'decision' => $decision
            ];
        });
        
        $graph->addNode('end', function ($state) {
            return [
                'step' => 'end',
                'message' => 'Workflow completed',
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
            'continue' => 'process',
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
            'workflow' => 'example'
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}