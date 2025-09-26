<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\ChannelsState;
use Illuminate\Support\Facades\Log;

use LangGraph\Model\Factory\ModelFactory;

class ChatGraphController extends Controller
{
    /**
     * Process a chat conversation with context management (non-streaming)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processChat(Request $request)
    {
        try {
            // Get request data
            $message = $request->input('message');
            $modelType = $request->input('model_type', 'qwen');
            $conversationId = $request->input('conversation_id', uniqid('conv_'));
            $history = $request->input('history', []);

            // Validate required inputs
            if (empty($message)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please provide a message'
                ], 400);
            }

            // Create a simple state graph for conversation context
            $graph = new StateGraph(ChannelsState::class);

            // Add nodes for chat processing
            $graph->addNode('input_processor', function ($state) use ($message, $history, $conversationId, $modelType) {
                // Process user input and update context
                $messages = $history ?? [];
                
                // Add user message
                $messages[] = ['role' => 'user', 'content' => $message];
                
                return [
                    'messages' => $messages,
                    'conversation_id' => $conversationId,
                    'model_type' => $modelType,
                    'status' => 'processing_input'
                ];
            });

            // Add node for AI response (non-streaming)
            $graph->addNode('ai_responder', function ($state) {
                $messages = $state['messages'] ?? [];
                $modelType = $state['model_type'] ?? 'qwen';
                
                try {
                    // Get the user's last message
                    $userMessage = '';
                    foreach (array_reverse($messages) as $msg) {
                        if ($msg['role'] === 'user') {
                            $userMessage = $msg['content'];
                            break;
                        }
                    }
                    
                    // Create model factory with API keys from config
                    $modelConfigs = [
                        'deepseek_api_key' => config('services.deepseek.key'),
                        'qwen_api_key' => config('services.qwen.key')
                    ];
                    
                    $factory = new ModelFactory($modelConfigs);
                    $client = $factory->createClient($modelType);
                    
                    // Generate AI response using the actual model
                    $response = $client->chatComplete($messages);
                    
                    return [
                        'status' => 'response_complete',
                        'response' => $response
                    ];
                } catch (\Exception $e) {
                    // Log the error for debugging
                    Log::error('AI Responder Error: ' . $e->getMessage(), [
                        'trace' => $e->getTraceAsString(),
                        'model_type' => $modelType,
                        'messages_count' => count($messages)
                    ]);
                    
                    return [
                        'status' => 'error',
                        'error' => 'AI service temporarily unavailable: ' . $e->getMessage()
                    ];
                }
            });

            $graph->addNode('response_formatter', function ($state) {
                $response = $state['response'] ?? '';
                $messages = $state['messages'] ?? [];
                
                // Add AI response to messages
                if (!empty($response)) {
                    $messages[] = ['role' => 'assistant', 'content' => $response];
                }
                
                return [
                    'messages' => $messages,
                    'status' => 'response_formatted',
                    'final_response' => $response
                ];
            });

            $graph->addNode('context_updater', function ($state) {
                // Update context and prepare for next interaction
                return [
                    'status' => 'waiting_for_input',
                    'conversation_id' => $state['conversation_id'] ?? uniqid('conv_')
                ];
            });

            // Add edges
            $graph->addEdge('input_processor', 'ai_responder');
            $graph->addEdge('ai_responder', 'response_formatter');
            $graph->addEdge('response_formatter', 'context_updater');
            
            // Set entry and finish points
            $graph->setEntryPoint('input_processor');
            $graph->setFinishPoint('context_updater');

            // Compile the graph
            $compiled = $graph->compile();

            // Create initial state with channel definitions
            $channelDefs = [
                'conversation_id' => ['type' => 'last_value'],
                'model_type' => ['type' => 'last_value'],
                'messages' => ['type' => 'last_value'],
                'status' => ['type' => 'last_value'],
                'response' => ['type' => 'last_value'],
                'error' => ['type' => 'last_value'],
                'final_response' => ['type' => 'last_value']
            ];
            
            $initialState = new ChannelsState($channelDefs, [
                'conversation_id' => $conversationId,
                'model_type' => $modelType
            ]);

            // Execute the workflow
            $finalState = $compiled->execute($initialState);

            // Return the final state as JSON
            return response()->json([
                'success' => true,
                'data' => $finalState->getData()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get conversation history
     *
     * @param Request $request
     * @param string $conversationId
     * @return JsonResponse
     */
    public function getConversationHistory(Request $request, $conversationId)
    {
        try {
            // In a real implementation, you would fetch the conversation history from a database
            // For this example, we'll return a mock response
            return response()->json([
                'success' => true,
                'data' => [
                    'conversation_id' => $conversationId,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => 'Hello!'],
                        ['role' => 'assistant', 'content' => 'Hi there! How can I help you today?']
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}