<?php

namespace App\Agent\Example;

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;
use App\Agent\AgentFactory;
use App\Agent\Tool\ToolManager;
use App\Agent\Tool\CalculatorTool;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

class MultiAgentWorkflow
{
    public static function create(array $configData = []): StateGraph
    {
        // 创建模型配置和工厂
        $modelConfig = new ModelConfig($configData);
        $modelFactory = new ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建图
        $graph = new StateGraph(State::class);
        
        // 创建响应代理
        $researcherAgent = $agentFactory->createResponseAgent(
            'researcher',
            'Research result: Based on the task, I have gathered relevant information.',
            'Research and analysis specialist'
        );
        
        $plannerAgent = $agentFactory->createResponseAgent(
            'planner',
            'Plan: Based on the research, I have created a detailed plan.',
            'Planning and strategy specialist'
        );
        
        $executorAgent = $agentFactory->createResponseAgent(
            'executor',
            'Execution result: I have executed the plan and obtained the results.',
            'Task execution specialist'
        );
        
        $reviewerAgent = $agentFactory->createResponseAgent(
            'reviewer',
            'Review result: I have reviewed the execution and validated the results.',
            'Quality assurance specialist'
        );
        
        // 添加节点
        $graph->addNode('researcher', function ($state) use ($researcherAgent) {
            $task = $state['task'] ?? 'Perform research';
            $result = $researcherAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), ['_currentNode' => 'researcher']);
        });
        
        $graph->addNode('planner', function ($state) use ($plannerAgent) {
            $task = $state['research_result'] ?? 'Create a plan';
            $result = $plannerAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), ['_currentNode' => 'planner']);
        });
        
        $graph->addNode('executor', function ($state) use ($executorAgent) {
            $task = $state['plan'] ?? 'Execute the plan';
            $result = $executorAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), ['_currentNode' => 'executor']);
        });
        
        $graph->addNode('reviewer', function ($state) use ($reviewerAgent) {
            $task = $state['execution_result'] ?? 'Review the results';
            $result = $reviewerAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), [
                '_currentNode' => 'reviewer',
                'completed' => true,
                'status' => 'finished'
            ]);
        });
        
        // 添加边
        $graph->addEdge('researcher', 'planner');
        $graph->addEdge('planner', 'executor');
        $graph->addEdge('executor', 'reviewer');
        
        // 设置起始和结束节点
        $graph->setEntryPoint('researcher');
        $graph->setFinishPoint('reviewer');
        
        return $graph;
    }
    
    public static function run(string $task = 'Calculate the sum of 10 and 20, then multiply by 5', array $configData = []): State
    {
        // 创建图
        $graph = self::create($configData);
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'task' => $task,
            'user_input' => $task
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}