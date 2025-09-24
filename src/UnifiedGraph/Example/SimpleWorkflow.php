<?php

namespace UnifiedGraph\Example;

use UnifiedGraph\Executor\Executor;
use UnifiedGraph\Node\AbstractNode;
use UnifiedGraph\Edge\Edge;
use UnifiedGraph\State\State;

// 定义节点类
class StartNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 初始化工作流状态
        $state['step'] = 'start';
        $state['message'] = 'Workflow started';
        $state['timestamp'] = date('Y-m-d H:i:s');
        
        return $state;
    }
}

class ProcessNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 处理逻辑
        $state['step'] = 'processing';
        $state['processed_data'] = 'Data processed at ' . date('Y-m-d H:i:s');
        
        // 模拟一些处理时间
        sleep(1);
        
        return $state;
    }
}

class DecisionNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 决策节点，根据某些条件决定下一步
        $state['step'] = 'decision';
        
        // 模拟决策逻辑
        $random = rand(0, 1);
        if ($random === 0) {
            $state['decision'] = 'path_a';
            $state['message'] = 'Taking path A';
        } else {
            $state['decision'] = 'path_b';
            $state['message'] = 'Taking path B';
        }
        
        return $state;
    }
}

class EndNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 结束节点
        $state['step'] = 'end';
        $state['message'] = 'Workflow completed';
        $state['completed'] = true;
        
        return $state;
    }
}

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