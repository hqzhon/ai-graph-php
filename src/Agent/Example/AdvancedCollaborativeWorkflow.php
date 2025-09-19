<?php

namespace App\Agent\Example;

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;
use App\Agent\AgentFactory;
use App\Agent\Collaboration\AdvancedCollaborationProtocol;
use App\Agent\Collaboration\SmartTaskAllocator;
use App\Agent\Collaboration\AdvancedCoordinator;
use App\Agent\Collaboration\AdvancedDecisionMechanism;
use App\Agent\Collaboration\AdvancedSwarmIntelligence;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

class AdvancedCollaborativeWorkflow
{
    public static function create(array $configData = []): StateGraph
    {
        // 创建模型配置和工厂
        $modelConfig = new ModelConfig($configData);
        $modelFactory = new ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建智能体
        $researcherAgent = $agentFactory->createResponseAgent(
            'researcher',
            'Research result: Based on the task, I have gathered relevant information about complex solutions.',
            'Research and analysis specialist'
        );
        
        $plannerAgent = $agentFactory->createResponseAgent(
            'planner',
            'Plan: Based on the research, I have created a detailed complex plan.',
            'Planning and strategy specialist'
        );
        
        $executorAgent = $agentFactory->createResponseAgent(
            'executor',
            'Execution result: I have executed the complex plan and obtained the results.',
            'Task execution specialist'
        );
        
        $reviewerAgent = $agentFactory->createResponseAgent(
            'reviewer',
            'Review result: I have reviewed the execution and validated the complex results.',
            'Quality assurance specialist'
        );
        
        // 创建图
        $graph = new StateGraph(State::class);
        
        // 添加节点
        $graph->addNode('researcher', function ($state) use ($researcherAgent) {
            $task = $state['task'] ?? 'Perform research';
            $result = $researcherAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), [
                '_currentNode' => 'researcher',
                'research_findings' => 'Research findings on complex topics'
            ]);
        });
        
        $graph->addNode('planner', function ($state) use ($plannerAgent) {
            $task = $state['research_findings'] ?? 'Create a plan';
            $result = $plannerAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), [
                '_currentNode' => 'planner',
                'plan_complexity' => 'complex'
            ]);
        });
        
        $graph->addNode('executor', function ($state) use ($executorAgent) {
            $task = $state['plan'] ?? 'Execute the plan';
            $result = $executorAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), [
                '_currentNode' => 'executor'
            ]);
        });
        
        $graph->addNode('reviewer', function ($state) use ($reviewerAgent) {
            $task = $state['execution_result'] ?? 'Review the results';
            $result = $reviewerAgent->execute($task, new State($state));
            return array_merge($state, $result->getData(), [
                '_currentNode' => 'reviewer',
                'quality_score' => 0.95,
                'completed' => true,
                'status' => 'finished'
            ]);
        });
        
        $graph->addNode('end', function ($state) {
            return array_merge($state, [
                '_currentNode' => 'end',
                'completed' => true,
                'status' => 'finished'
            ]);
        });
        
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
                return 'executor';
            }
            return 'executor';
        }, [
            'executor' => 'executor'
        ]);
        
        $graph->addEdge('executor', 'reviewer');
        
        // 添加反馈循环
        $graph->addConditionalEdges('reviewer', function ($state) {
            // 根据评审结果决定是否需要重新执行
            $qualityScore = $state['quality_score'] ?? 1.0;
            if ($qualityScore < 0.8) {
                return 'researcher';
            }
            return 'end';
        }, [
            'researcher' => 'researcher',
            'end' => 'end'
        ]);
        
        // 设置起始和结束节点
        $graph->setEntryPoint('researcher');
        $graph->setFinishPoint('end');
        
        return $graph;
    }
    
    public static function run(string $task = 'Research and develop a solution for reducing energy consumption in data centers', array $configData = []): State
    {
        // 创建图
        $graph = self::create($configData);
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new State([
            'task' => $task,
            'user_input' => $task,
            'workflow_type' => 'advanced_collaborative'
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}