<?php

namespace App\Agent\Example;

use App\Agent\Graph\AgentGraph;
use App\Agent\AgentFactory;
use App\Agent\Collaboration\AdvancedCollaborationProtocol;
use App\Agent\Collaboration\SmartTaskAllocator;
use App\Agent\Collaboration\AdvancedCoordinator;
use App\Agent\Collaboration\AdvancedDecisionMechanism;
use App\Agent\Collaboration\AdvancedSwarmIntelligence;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\LangGraph\State\GraphState;

class AdvancedCollaborativeWorkflow
{
    public static function create(): AgentGraph
    {
        // 创建模型配置和工厂
        $modelConfig = new ModelConfig();
        $modelFactory = new ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建协作组件
        $collaborationProtocol = new AdvancedCollaborationProtocol();
        $taskAllocator = new SmartTaskAllocator();
        $coordinator = new AdvancedCoordinator();
        $decisionMechanism = new AdvancedDecisionMechanism();
        $swarmIntelligence = new AdvancedSwarmIntelligence();
        
        // 创建图
        $graph = new AgentGraph(GraphState::class);
        
        // 创建智能体
        $researcherAgent = $agentFactory->createModelBasedAgent(
            'researcher',
            'deepseek',
            'You are a research assistant. Your role is to gather information and analyze data.',
            'Research and analysis specialist'
        );
        
        $plannerAgent = $agentFactory->createModelBasedAgent(
            'planner',
            'deepseek',
            'You are a planning assistant. Your role is to create detailed plans and strategies.',
            'Planning and strategy specialist'
        );
        
        $executorAgent = $agentFactory->createModelBasedAgent(
            'executor',
            'qwen',
            'You are an execution assistant. Your role is to carry out planned tasks efficiently.',
            'Task execution specialist'
        );
        
        $reviewerAgent = $agentFactory->createModelBasedAgent(
            'reviewer',
            'qwen',
            'You are a review assistant. Your role is to review work and ensure quality.',
            'Quality assurance specialist'
        );
        
        // 添加智能体到图中
        $graph->addAgent('researcher', $researcherAgent);
        $graph->addAgent('planner', $plannerAgent);
        $graph->addAgent('executor', $executorAgent);
        $graph->addAgent('reviewer', $reviewerAgent);
        
        // 添加条件边，实现智能协作
        $graph->addConditionalEdges('researcher', function ($state) {
            // 根据研究结果决定下一步
            $findings = $state['research_findings'] ?? '';
            if (strpos($findings, 'complex') !== false) {
                return 'planner';
            }
            return 'executor';
        }, [
            'planner' => 'planner',
            'executor' => 'executor'
        ]);
        
        $graph->addConditionalEdges('planner', function ($state) {
            // 根据计划复杂度决定下一步
            $planComplexity = $state['plan_complexity'] ?? 'simple';
            if ($planComplexity === 'complex') {
                return 'collaborative_execution';
            }
            return 'executor';
        }, [
            'collaborative_execution' => 'executor',
            'executor' => 'executor'
        ]);
        
        $graph->addEdge('executor', 'reviewer');
        
        // 添加反馈循环
        $graph->addConditionalEdges('reviewer', function ($state) {
            // 根据评审结果决定是否需要重新执行
            $qualityScore = $state['quality_score'] ?? 1.0;
            if ($qualityScore < 0.8) {
                return 'rework';
            }
            return 'complete';
        }, [
            'rework' => 'researcher',
            'complete' => 'end'
        ]);
        
        // 设置起始和结束节点
        $graph->setEntryPoint('researcher');
        $graph->setFinishPoint('end');
        
        return $graph;
    }
    
    public static function run(string $task = 'Research and develop a solution for reducing energy consumption in data centers'): GraphState
    {
        // 创建图
        $graph = self::create();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new GraphState([
            'task' => $task,
            'user_input' => $task,
            'workflow_type' => 'advanced_collaborative'
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}