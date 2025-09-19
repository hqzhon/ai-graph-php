<?php

namespace App\Examples\Controller;

use App\Examples\Http\Response;
use App\View\Template;
use App\Service\WorkflowService;

class MultiAgentController
{
    private $template;
    private $workflowService;

    public function __construct(Template $template, WorkflowService $workflowService)
    {
        $this->template = $template;
        $this->workflowService = $workflowService;
    }

    public function lab()
    {
        $content = $this->template->render('workflow_lab', [
            'title' => 'Multi-Agent Workflow Lab',
        ]);
        return new Response($content);
    }

    public function streamWorkflow()
    {
        $this->setupStreaming();

        try {
            // Get POST data
            $workflowType = $_POST['workflow_type'] ?? 'simple';
            $task = $_POST['task'] ?? 'Research ways to improve energy efficiency in data centers';

            // Validate required inputs
            if (empty($task)) {
                echo "data: " . json_encode(["status" => "error", "message" => "Please provide a task description"]) . "\n\n";
                flush();
                exit();
            }

            // Validate workflow type
            if (!in_array($workflowType, ['simple', 'advanced'])) {
                echo "data: " . json_encode(["status" => "error", "message" => "Invalid workflow type selected"]) . "\n\n";
                flush();
                exit();
            }

            $configData = [
                'deepseek_api_key' => $_POST['deepseek_key'] ?? '',
                'qwen_api_key' => $_POST['qwen_key'] ?? '',
            ];

            // Check if at least one API key is provided
            if (empty($configData['deepseek_api_key']) && empty($configData['qwen_api_key'])) {
                // Log for debugging
                error_log("MultiAgentController: No API keys provided in form - relying on environment variables");
            }

            $initialStateData = [
                'task' => $task,
                'user_input' => $task,
                'workflow_type' => $workflowType,
                'messages' => [], // Initialize messages array
            ];

            echo "data: " . json_encode(["status" => "started", "message" => "Workflow started"]) . "\n\n";
            flush();

            $finalState = null;
            foreach ($this->workflowService->streamWorkflow($workflowType, $configData, $initialStateData) as $stepState) {
                echo "data: " . json_encode(["status" => "streaming", "step_state" => $stepState->getData()]) . "\n\n";
                flush();
                $finalState = $stepState;
            }

            if ($finalState) {
                echo "data: " . json_encode(["status" => "completed", "final_state" => $finalState->getData()]) . "\n\n";
                flush();
            }

        } catch (\Exception $e) {
            error_log("MultiAgentController Exception: " . $e->getMessage());
            echo "data: " . json_encode(["status" => "error", "message" => $e->getMessage()]) . "\n\n";
            flush();
        }

        echo "data: " . json_encode(["status" => "finished"]) . "\n\n";
        flush();
        exit();
    }

    private function setupStreaming()
    {
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', 1);
        }
        ini_set('zlib.output_compression', 0);
        ini_set('implicit_flush', 1);

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('Access-Control-Allow-Origin: *');
        header('X-Accel-Buffering: no');
    }
}