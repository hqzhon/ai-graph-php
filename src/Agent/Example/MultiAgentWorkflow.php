<?php

namespace App\Agent\Example;

use App\Agent\Graph\AgentGraph;
use App\Agent\AgentFactory;
use App\Agent\Tool\ToolManager;
use App\Agent\Tool\CalculatorTool;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\LangGraph\State\GraphState;

class MultiAgentWorkflow
{
    public static function create(): AgentGraph
    {
        // 创建模型配置和工厂
        $modelConfig = new ModelConfig();
        $modelFactory = new ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建工具管理器并注册工具
        $toolManager = new ToolManager();
        $toolManager->register(new CalculatorTool());
        
        // 创建图
        $graph = new AgentGraph(GraphState::class);
        
        // 创建智能体
        $plannerAgent = $agentFactory->createModelBasedAgent(
            'planner',
            'deepseek', // 或 'qwen'
            'You are a planning assistant. Your role is to break down complex tasks into simpler steps.',
            'Plans and breaks down tasks'
        );
        
        $executorAgent = $agentFactory->createModelBasedAgent(
            'executor',
            'deepseek', // 或 'qwen'
            'You are an execution assistant. Your role is to execute planned tasks.',
            'Executes planned tasks'
        );
        
        $reviewerAgent = $agentFactory->createModelBasedAgent(
            'reviewer',
            'deepseek', // 或 'qwen'
            'You are a review assistant. Your role is to review and validate results.',
            'Reviews and validates results'
        );
        
        // 添加智能体到图中
        $graph->addAgent('planner', $plannerAgent);
        $graph->addAgent('executor', $executorAgent);
        $graph->addAgent('reviewer', $reviewerAgent);
        
        // 添加边
        $graph->addEdge('planner', 'executor');
        $graph->addEdge('executor', 'reviewer');
        
        // 设置起始和结束节点
        $graph->setEntryPoint('planner');
        $graph->setFinishPoint('reviewer');
        
        return $graph;
    }
    
    public static function run(string $task = 'Calculate the sum of 10 and 20, then multiply by 5'): GraphState
    {
        // 创建图
        $graph = self::create();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new GraphState([
            'task' => $task,
            'user_input' => $task
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}