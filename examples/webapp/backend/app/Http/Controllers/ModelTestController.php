<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LangGraph\Model\Factory\ModelFactory;
use Illuminate\Support\Facades\Config;

class ModelTestController extends Controller
{
    /**
     * Test an AI model with a given prompt.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testModel(Request $request)
    {
        try {
            $modelType = $request->input('model_type', 'deepseek');
            $prompt = $request->input('prompt');
            $modelName = $request->input('model_name');

            // Get API keys from the request, which override config/env keys for one-off tests
            $requestKeys = [
                'deepseek' => $request->input('deepseek_key'),
                'qwen' => $request->input('qwen_key'),
            ];

            if (empty($prompt)) {
                return response()->json(['success' => false, 'error' => 'Please provide a prompt'], 400);
            }

            if (!in_array($modelType, ['deepseek', 'qwen'])) {
                return response()->json(['success' => false, 'error' => 'Invalid model type selected'], 400);
            }

            // Prepare model configuration, merging keys from config and request
            $apiKey = $requestKeys[$modelType] ?? Config::get("services.{$modelType}.key");

            if (empty($apiKey)) {
                 return response()->json(['success' => false, 'error' => "API key for {$modelType} not found. Please provide it in the request or set it in your .env file."], 400);
            }

            $modelConfigs = [
                $modelType . '_api_key' => $apiKey
            ];

            // Create the model client using the factory
            $factory = new ModelFactory($modelConfigs);
            $client = $factory->createClient($modelType);

            // Call the model
            $messages = [['role' => 'user', 'content' => $prompt]];
            $response = $client->chatComplete($messages);

            return response()->json([
                'success' => true,
                'data' => [
                    'model_type' => $modelType,
                    'prompt' => $prompt,
                    'response' => $response,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while communicating with the model: ' . $e->getMessage(),
            ], 500);
        }
    }
}