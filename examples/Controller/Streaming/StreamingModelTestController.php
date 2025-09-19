<?php

namespace App\Examples\Controller\Streaming;

use App\Examples\Http\Response;
use App\View\Template;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

class StreamingModelTestController
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
            'title' => 'AI Model Tester with Streaming',
        ]);
        return new Response($content);
    }

    public function streamModelResponse()
    {
        // Set up streaming headers
        $this->setupStreaming();

        try {
            // Get POST data
            $postData = $_POST;
            $modelType = $postData['model_type'] ?? 'qwen';
            $prompt = $postData['prompt'] ?? 'Hello, what is the capital of France?';

            // Validate required inputs
            if (empty($prompt)) {
                echo "data: " . json_encode(["status" => "error", "message" => "Please provide a prompt"]) . "\n\n";
                flush();
                exit();
            }

            // Validate model type
            if (!in_array($modelType, ['deepseek', 'qwen'])) {
                echo "data: " . json_encode(["status" => "error", "message" => "Invalid model type selected"]) . "\n\n";
                flush();
                exit();
            }

            // Get API keys with proper fallback chain
            $deepseekKey = $postData['deepseek_key'] ?? $_ENV['DEEPSEEK_API_KEY'] ?? $_SERVER['DEEPSEEK_API_KEY'] ?? getenv('DEEPSEEK_API_KEY') ?? '';
            $qwenKey = $postData['qwen_key'] ?? $_ENV['QWEN_API_KEY'] ?? $_SERVER['QWEN_API_KEY'] ?? getenv('QWEN_API_KEY') ?? '';

            // Check if we have at least one API key
            if (empty($deepseekKey) && empty($qwenKey)) {
                echo "data: " . json_encode(["status" => "error", "message" => "Please provide at least one API key"]) . "\n\n";
                flush();
                exit();
            }

            // Create configuration and factory
            $config = new ModelConfig([
                'deepseek_api_key' => $deepseekKey,
                'qwen_api_key' => $qwenKey,
            ]);
            
            $factory = new ModelFactory($config->all());
            $client = $factory->createClient($modelType);

            // Set model name if provided
            if (isset($postData['model_name']) && !empty($postData['model_name'])) {
                $client->setModelName($postData['model_name']);
            }

            // Prepare messages
            $messages = [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt]
            ];

            // Send start event
            echo "data: " . json_encode(["status" => "started", "message" => "Streaming started"]) . "\n\n";
            flush();

            // Stream the response character by character
            $characterCount = 0;
            foreach ($client->streamChatComplete($messages) as $character) {
                echo "data: " . json_encode(["status" => "streaming", "character" => $character, "position" => $characterCount]) . "\n\n";
                flush();
                $characterCount++;
            }

            // Send completion event
            echo "data: " . json_encode(["status" => "completed", "total_characters" => $characterCount]) . "\n\n";
            flush();

        } catch (\Exception $e) {
            echo "data: " . json_encode(["status" => "error", "message" => $e->getMessage()]) . "\n\n";
            flush();
        }

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