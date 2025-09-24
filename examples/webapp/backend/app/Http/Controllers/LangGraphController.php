<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use UnifiedGraph\StateGraph;
use UnifiedGraph\State\State;

class LangGraphController extends Controller
{
    /**
     * Execute a simple LangGraph workflow
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function executeSimpleWorkflow(Request $request)
    {
        try {
            // Create a simple workflow
            $graph = new StateGraph(State::class);
            
            // Add nodes
            $graph->addNode('start', function ($state) {
                return ['step' => 'start', 'message' => 'Workflow started'];
            });
            
            $graph->addNode('process', function ($state) {
                return ['step' => 'process', 'message' => 'Processing data'];
            });
            
            $graph->addNode('end', function ($state) {
                return ['step' => 'end', 'message' => 'Workflow completed'];
            });
            
            // Add edges
            $graph->addEdge('start', 'process');
            $graph->addEdge('process', 'end');
            
            // Set entry and finish points
            $graph->setEntryPoint('start');
            $graph->setFinishPoint('end');
            
            // Compile and execute
            $compiled = $graph->compile();
            $initialState = new State(['workflow' => 'web_example']);
            $finalState = $compiled->execute($initialState);
            
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
     * Execute an advanced LangGraph workflow with channels
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function executeAdvancedWorkflow(Request $request)
    {
        try {
            // Get task from request
            $task = $request->input('task', 'Default advanced workflow task');
            
            // Create a state graph for advanced workflow
            $graph = new StateGraph(State::class);
            
            // Add nodes
            $graph->addNode('start', function ($state) use ($task) {
                return [
                    'task' => $task,
                    'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
                    'step' => 'start'
                ];
            });
            
            $graph->addNode('analyze', function ($state) {
                $task = $state['task'] ?? 'Default task';
                $messages = $state['messages'] ?? [];
                $messages[] = ['role' => 'user', 'content' => "Please analyze this task: $task"];
                return [
                    'messages' => $messages,
                    'step' => 'analyze',
                    'analysis' => "Task analysis completed for: $task"
                ];
            });
            
            $graph->addNode('process', function ($state) {
                $messages = $state['messages'] ?? [];
                $analysis = $state['analysis'] ?? 'No analysis available';
                $messages[] = ['role' => 'assistant', 'content' => "Based on the analysis: $analysis, I will process this task."];
                return [
                    'messages' => $messages,
                    'step' => 'process',
                    'processing_result' => 'Task processing completed successfully'
                ];
            });
            
            $graph->addNode('end', function ($state) {
                $messages = $state['messages'] ?? [];
                $result = $state['processing_result'] ?? 'No result available';
                $messages[] = ['role' => 'assistant', 'content' => "Task completed. Result: $result"];
                return [
                    'messages' => $messages,
                    'step' => 'end',
                    'final_result' => $result
                ];
            });
            
            // Add edges
            $graph->addEdge('start', 'process');
            $graph->addEdge('process', 'end');
            
            // Set entry and finish points
            $graph->setEntryPoint('start');
            $graph->setFinishPoint('end');
            
            // Compile and execute
            $compiled = $graph->compile();
            $initialState = new State(['workflow' => 'advanced_web_example']);
            $finalState = $compiled->execute($initialState);
            
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
}