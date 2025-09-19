<?php

namespace App\Agent\Example;

use App\Agent\AgentFactory;
use App\Agent\Collaboration\AdvancedSwarmIntelligence;
use App\Agent\ResponseAgent;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\UnifiedGraph\State\State;

class SwarmIntelligenceExample
{
    public static function run(): array
    {
        // 创建模型配置和工厂
        $modelConfig = new ModelConfig();
        $modelFactory = new ModelFactory($modelConfig->all());
        $agentFactory = new AgentFactory($modelFactory);
        
        // 创建智能体
        $agents = [
            new ResponseAgent('agent_1', 'Response from agent 1', 'Specialist in area A'),
            new ResponseAgent('agent_2', 'Response from agent 2', 'Specialist in area B'),
            new ResponseAgent('agent_3', 'Response from agent 3', 'Specialist in area C'),
            new ResponseAgent('agent_4', 'Response from agent 4', 'Generalist'),
        ];
        
        // 创建群体智能实例
        $swarmIntelligence = new AdvancedSwarmIntelligence();
        
        // 创建初始状态
        $state = new State([
            'problem' => 'How to optimize a complex system?',
            'environment_state' => [
                'complexity' => 'high',
                'resources' => 'limited',
                'time_constraint' => 'moderate'
            ]
        ]);
        
        // 应用群体智能
        $updatedState = $swarmIntelligence->applySwarmIntelligence($state);
        
        // 生成解决方案
        $solutions = [
            'Solution A: Approach based on agent_1 expertise',
            'Solution B: Approach based on agent_2 expertise',
            'Solution C: Approach based on agent_3 expertise',
            'Solution D: General approach from agent_4'
        ];
        
        // 模拟群体行为结果
        $swarmResult = $updatedState->get('swarm_intelligence', []);
        
        // 评估性能
        $performance = [
            'swarm_size' => $swarmResult['swarm_size'] ?? 0,
            'intelligence_score' => $swarmResult['collective_intelligence_score'] ?? 0,
            'optimization_applied' => $swarmResult['optimization_applied'] ?? false
        ];
        
        return [
            'final_state' => $updatedState->getData(),
            'solutions' => $solutions,
            'performance_metrics' => $performance
        ];
    }
}