<?php

namespace App\Graph\Example;

use App\Graph\Executor\Executor;
use App\Graph\Node\StartNode;
use App\Graph\Node\ProcessNode;
use App\Graph\Node\DecisionNode;
use App\Graph\Node\EndNode;
use App\Graph\Edge\Edge;
use App\Graph\State\State;

class SimpleWorkflow
{
    public static function create(): Executor
    {
        $executor = new Executor();
        
        // 添加节点
        $startNode = new StartNode('start');
        $processNode = new ProcessNode('process');
        $decisionNode = new DecisionNode('decision');
        $endNode = new EndNode('end');
        
        $executor->addNode($startNode);
        $executor->addNode($processNode);
        $executor->addNode($decisionNode);
        $executor->addNode($endNode);
        
        // 添加边
        $executor->addEdge(new Edge('start', 'process'));
        $executor->addEdge(new Edge('process', 'decision'));
        $executor->addEdge(new Edge('decision', 'end'));
        
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
            'workflow_name' => 'Simple Workflow',
            'started_at' => date('Y-m-d H:i:s')
        ]);
        
        // 执行工作流
        $finalState = $executor->execute($initialState);
        
        return $finalState;
    }
}