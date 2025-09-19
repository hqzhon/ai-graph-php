<?php

namespace App\Examples\Controller;

use App\Examples\Http\Response;
use App\View\Template;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

class ModelTestController
{
    private $template;
    private $modelFactory;

    public function __construct(Template $template, ModelFactory $modelFactory)
    {
        $this->template = $template;
        $this->modelFactory = $modelFactory;
    }

    public function showForm()
    {
        $content = $this->template->render('model_test', [
            'title' => 'AI Model Tester',
        ]);
        return new Response($content);
    }

    public function processForm()
    {
        $postData = $_POST;
        $modelType = $postData['model_type'] ?? 'deepseek';
        $prompt = $postData['prompt'] ?? 'Hello, what is the capital of France?';

        $viewVariables = [
            'title' => 'AI Model Tester',
            'defaults' => $postData,
        ];

        // Validate required inputs
        if (empty($prompt)) {
            $viewVariables['error'] = 'Please provide a prompt';
            return new Response($this->template->render('model_test', $viewVariables));
        }

        // Validate model type
        if (!in_array($modelType, ['deepseek', 'qwen'])) {
            $viewVariables['error'] = 'Invalid model type selected';
            return new Response($this->template->render('model_test', $viewVariables));
        }

        // Get API keys with proper fallback chain
        $deepseekKey = $postData['deepseek_key'] ?? $_ENV['DEEPSEEK_API_KEY'] ?? $_SERVER['DEEPSEEK_API_KEY'] ?? getenv('DEEPSEEK_API_KEY') ?? '';
        $qwenKey = $postData['qwen_key'] ?? $_ENV['QWEN_API_KEY'] ?? $_SERVER['QWEN_API_KEY'] ?? getenv('QWEN_API_KEY') ?? '';
        
        // Log for debugging (can be removed in production)
        error_log("ModelTestController Debug:");
        error_log("POST deepseek_key: " . ($postData['deepseek_key'] ?? 'NOT SET'));
        error_log("POST qwen_key: " . ($postData['qwen_key'] ?? 'NOT SET'));
        error_log("Final deepseekKey: '" . $deepseekKey . "'");
        error_log("Final qwenKey: '" . $qwenKey . "'");
        
        // Check if we have at least one API key
        if (empty($deepseekKey) && empty($qwenKey)) {
            error_log("ERROR: Please provide at least one API key");
            $viewVariables['error'] = 'Please provide at least one API key';
            return new Response($this->template->render('model_test', $viewVariables));
        }

        try {
            // Create configuration object
            $config = new ModelConfig([
                'deepseek_api_key' => $deepseekKey,
                'qwen_api_key' => $qwenKey,
            ]);
            
            $factory = new ModelFactory($config->all());

            $client = $factory->createClient($modelType);

            if (isset($postData['model_name']) && !empty($postData['model_name'])) {
                $client->setModelName($postData['model_name']);
            }

            $messages = [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt]
            ];

            $viewVariables['response'] = $client->chatComplete($messages);

        } catch (\Exception $e) {
            error_log("Exception: " . $e->getMessage());
            $viewVariables['error'] = 'Error: ' . $e->getMessage();
        }
        
        return new Response($this->template->render('model_test', $viewVariables));
    }
}