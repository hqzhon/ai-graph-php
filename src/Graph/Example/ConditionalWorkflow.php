<?php

namespace App\Graph\Example;

use App\Graph\Executor\Executor;
use App\Graph\Node\StartNode;
use App\Graph\Node\ProcessNode;
use App\Graph\Node\DecisionNode;
use App\Graph\Node\EndNode;
use App\Graph\Edge\Edge;
use App\Graph\State\State;

class ConditionalWorkflow
{
    public static function create(): Executor
    {
        $executor = new Executor();
        
        // 添加节点
        $startNode = new StartNode('start');
        $processNodeA = new ProcessNode('process_a');
        $processNodeB = new ProcessNode('process_b');
        $endNode = new EndNode('end');
        
        $executor->addNode($startNode);
        $executor->addNode($processNodeA);
        $executor->addNode($processNodeB);
        $executor->addNode($endNode);
        
        // 添加边，带条件
        $executor->addEdge(new Edge('start', 'process_a', function($state) {
            return isset($state['decision']) && $state['decision'] === 'path_a';
        }));
        
        $executor->addEdge(new Edge('start', 'process_b', function($state) {
            return isset($state['decision']) && $state['decision'] === 'path_b';
        }));
        
        $executor->addEdge(new Edge('process_a', 'end'));
        $executor->addEdge(new Edge('process_b', 'end'));
        
        // 设置起始节点
        $executor->setStartNode('start');
        
        return $executor;
    }
    
    public static function run(): State
    {
        // 创建执行器
        $executor = self::create();
        
        // 创建初始状态
        $initialState = new State([
            'workflow_name' => 'Conditional Workflow',
            'started_at' => date('Y-m-d H:i:s'),
            'decision' => 'path_a' // 设置决策路径
        ]);
        
        // 执行工作流
        $finalState = $executor->execute($initialState);
        
        return $finalState;
    }
}