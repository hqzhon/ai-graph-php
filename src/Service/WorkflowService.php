<?php

namespace App\Service;

use App\Agent\AgentFactory;
use App\Agent\Example\AdvancedCollaborativeWorkflow;
use App\Agent\Example\MultiAgentWorkflow;
use App\UnifiedGraph\State\State;
use App\Model\Config\ModelConfig;

class WorkflowService
{
    private $agentFactory;

    public function __construct(AgentFactory $agentFactory)
    {
        $this->agentFactory = $agentFactory;
    }

    public function runWorkflow(string $workflowType, array $configData, array $initialStateData)
    {
        $graph = $this->createWorkflow($workflowType, $configData);
        $compiled = $graph->compile();
        $initialState = new State($initialStateData);
        return $compiled->execute($initialState);
    }

    public function streamWorkflow(string $workflowType, array $configData, array $initialStateData): \Generator
    {
        $graph = $this->createWorkflow($workflowType, $configData);
        $compiled = $graph->compile();
        $initialState = new State($initialStateData);
        yield from $compiled->stream($initialState);
    }

    private function createWorkflow(string $workflowType, array $configData)
    {
        // 创建模型配置，它会自动从环境变量中获取API密钥
        $modelConfig = new ModelConfig([
            'deepseek_api_key' => $configData['deepseek_api_key'] ?? '',
            'qwen_api_key' => $configData['qwen_api_key'] ?? '',
        ]);
        
        // 检查是否有可用的API密钥（来自POST数据或环境变量）
        $deepseekKey = $modelConfig->get('deepseek_api_key');
        $qwenKey = $modelConfig->get('qwen_api_key');
        
        // 如果没有任何API密钥，使用空配置
        if (empty($deepseekKey) && empty($qwenKey)) {
            $configArray = [];
        } else {
            $configArray = $modelConfig->all();
        }

        if ($workflowType === 'advanced') {
            return AdvancedCollaborativeWorkflow::create($configArray);
        } 
        
        return MultiAgentWorkflow::create($configArray);
    }

    public function runCustomWorkflow(array $configData, array $agentDefinitions, string $task)
    {
        // For custom workflows, we'll use the MultiAgentWorkflow as a base
        return MultiAgentWorkflow::run($task, $configData);
    }
}