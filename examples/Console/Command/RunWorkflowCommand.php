<?php

namespace App\Examples\Console\Command;

use App\Examples\Console\Command as BaseCommand;
use App\Service\WorkflowService;

class RunWorkflowCommand extends BaseCommand
{
    private $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        parent::__construct('workflow:run', 'Run a sample multi-agent workflow');
        $this->workflowService = $workflowService;
    }

    public function execute($args)
    {
        echo "Running Simple Multi-Agent Workflow...\n";

        try {
            // This requires API keys to be set in config/model.php or environment variables
            $configData = []; // Assumes config is loaded from environment or file by ModelConfig
            $task = 'Tell me a joke.';

            $initialStateData = [
                'task' => $task,
                'user_input' => $task,
                'workflow_type' => 'simple'
            ];

            $finalState = $this->workflowService->runWorkflow('simple', $configData, $initialStateData);

            echo "Workflow Final State:\n";
            print_r($finalState->getData());

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            echo "Please ensure API keys are configured in config/model.php or as environment variables.\n";
        }
    }
}