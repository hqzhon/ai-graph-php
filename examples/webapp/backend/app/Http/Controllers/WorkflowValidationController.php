<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\StreamedResponse;
use UnifiedGraph\StateGraph;
use UnifiedGraph\State\State;

class WorkflowValidationController extends Controller
{
    /**
     * Validate a workflow and stream the validation process
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function validateWorkflow(Request $request)
    {
        try {
            // Get request data
            $workflowDefinition = $request->input('workflow');
            $validationRules = $request->input('rules', []);
            $validationId = $request->input('validation_id', uniqid('val_'));

            // Validate required inputs
            if (empty($workflowDefinition)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please provide a workflow definition'
                ], 400);
            }

            // Create a state graph for validation tracking
            $graph = new StateGraph(State::class);

            // Add nodes for workflow validation
            $graph->addNode('input_validator', function ($state) use ($workflowDefinition, $validationRules, $validationId) {
                // Validate the input workflow definition
                $errors = [];
                
                // Check if workflow has required fields
                if (!isset($workflowDefinition['nodes']) || !is_array($workflowDefinition['nodes'])) {
                    $errors[] = 'Workflow must have a "nodes" array';
                }
                
                if (!isset($workflowDefinition['edges']) || !is_array($workflowDefinition['edges'])) {
                    $errors[] = 'Workflow must have an "edges" array';
                }
                
                return [
                    'validation_id' => $validationId,
                    'workflow_definition' => $workflowDefinition,
                    'validation_rules' => $validationRules,
                    'status' => empty($errors) ? 'input_valid' : 'input_invalid',
                    'validation_errors' => $errors,
                    'progress' => 10
                ];
            });

            $graph->addNode('structure_validator', function ($state) {
                $workflow = $state['workflow_definition'] ?? [];
                $errors = $state['validation_errors'] ?? [];
                
                // Validate workflow structure
                $nodes = $workflow['nodes'] ?? [];
                $edges = $workflow['edges'] ?? [];
                
                // Check for duplicate node names
                $nodeNames = array_keys($nodes);
                $duplicateNodes = array_diff_assoc($nodeNames, array_unique($nodeNames));
                if (!empty($duplicateNodes)) {
                    $errors[] = 'Duplicate node names found: ' . implode(', ', $duplicateNodes);
                }
                
                // Check if all edges reference existing nodes
                foreach ($edges as $edge) {
                    if (!isset($edge['from']) || !isset($edge['to'])) {
                        $errors[] = 'Edge must have "from" and "to" properties';
                        continue;
                    }
                    
                    if (!in_array($edge['from'], $nodeNames)) {
                        $errors[] = 'Edge references non-existent node: ' . $edge['from'];
                    }
                    
                    if (!in_array($edge['to'], $nodeNames)) {
                        $errors[] = 'Edge references non-existent node: ' . $edge['to'];
                    }
                }
                
                return [
                    'status' => empty($errors) ? 'structure_valid' : 'structure_invalid',
                    'validation_errors' => $errors,
                    'progress' => 30
                ];
            });

            $graph->addNode('rule_validator', function ($state) {
                $workflow = $state['workflow_definition'] ?? [];
                $rules = $state['validation_rules'] ?? [];
                $errors = $state['validation_errors'] ?? [];
                
                // Apply custom validation rules
                foreach ($rules as $rule) {
                    // In a real implementation, you would apply the actual rules
                    // For this example, we'll just simulate rule validation
                    if (isset($rule['type']) && $rule['type'] === 'required_nodes') {
                        $requiredNodes = $rule['nodes'] ?? [];
                        $existingNodes = array_keys($workflow['nodes'] ?? []);
                        $missingNodes = array_diff($requiredNodes, $existingNodes);
                        
                        if (!empty($missingNodes)) {
                            $errors[] = 'Missing required nodes: ' . implode(', ', $missingNodes);
                        }
                    }
                }
                
                return [
                    'status' => empty($errors) ? 'rules_valid' : 'rules_invalid',
                    'validation_errors' => $errors,
                    'progress' => 60
                ];
            });

            $graph->addNode('execution_simulator', function ($state) {
                $workflow = $state['workflow_definition'] ?? [];
                $errors = $state['validation_errors'] ?? [];
                
                // Simulate workflow execution to check for potential issues
                // In a real implementation, you would actually simulate the workflow
                // For this example, we'll just simulate the process
                
                // Simulate processing time
                sleep(1);
                
                return [
                    'status' => 'simulation_complete',
                    'progress' => 90
                ];
            });

            $graph->addNode('report_generator', function ($state) use ($validationId) {
                $errors = $state['validation_errors'] ?? [];
                $workflow = $state['workflow_definition'] ?? [];
                
                // Generate validation report
                $report = [
                    'validation_id' => $validationId,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'workflow_name' => $workflow['name'] ?? 'Unnamed Workflow',
                    'is_valid' => empty($errors),
                    'errors' => $errors,
                    'summary' => [
                        'total_nodes' => count($workflow['nodes'] ?? []),
                        'total_edges' => count($workflow['edges'] ?? []),
                        'error_count' => count($errors)
                    ]
                ];
                
                return [
                    'validation_report' => $report,
                    'status' => 'report_generated',
                    'progress' => 100
                ];
            });

            // Add edges
            $graph->addEdge('input_validator', 'structure_validator');
            $graph->addEdge('structure_validator', 'rule_validator');
            $graph->addEdge('rule_validator', 'execution_simulator');
            $graph->addEdge('execution_simulator', 'report_generator');
            
            // Set entry and finish points
            $graph->setEntryPoint('input_validator');
            $graph->setFinishPoint('report_generator');

            // Note: Checkpoint saver functionality simplified for this demo

            // Compile the graph
            $compiled = $graph->compile();

            // Create initial state
            $initialState = new State([
                'validation_id' => $validationId
            ]);

            // Return a streamed response
            return response()->stream(function () use ($compiled, $initialState) {
                try {
                    // Send initial message
                    echo 'data: ' . json_encode([
                        'status' => 'started',
                        'message' => 'Starting workflow validation...'
                    ]) . "\n\n";
                    flush();

                    // Execute the workflow and get final state
                    $finalState = $compiled->execute($initialState);
                    $data = $finalState->getData();
                    
                    // Send progress updates
                    $progressSteps = [
                        ['progress' => 10, 'status' => 'input_validation', 'message' => 'Validating input...'],
                        ['progress' => 30, 'status' => 'structure_validation', 'message' => 'Validating structure...'],
                        ['progress' => 60, 'status' => 'rule_validation', 'message' => 'Applying validation rules...'],
                        ['progress' => 90, 'status' => 'execution_simulation', 'message' => 'Simulating execution...'],
                        ['progress' => 100, 'status' => 'report_generation', 'message' => 'Generating report...']
                    ];
                    
                    foreach ($progressSteps as $step) {
                        echo 'data: ' . json_encode($step) . "\n\n";
                        flush();
                        usleep(500000); // 0.5 second delay for demo
                    }
                    
                    // Send final result
                    echo 'data: ' . json_encode($data) . "\n\n";
                    flush();

                    // Send finish message
                    echo 'data: ' . json_encode([
                        'status' => 'finished'
                    ]) . "\n\n";
                    flush();
                } catch (\Exception $e) {
                    echo 'data: ' . json_encode([
                        'status' => 'error',
                        'error' => $e->getMessage()
                    ]) . "\n\n";
                    flush();
                }
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Access-Control-Allow-Origin' => '*',
                'X-Accel-Buffering' => 'no'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get validation report
     *
     * @param Request $request
     * @param string $validationId
     * @return JsonResponse
     */
    public function getValidationReport(Request $request, $validationId)
    {
        try {
            // In a real implementation, you would fetch the validation report from a database
            // For this example, we'll return a mock response
            return response()->json([
                'success' => true,
                'data' => [
                    'validation_id' => $validationId,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'workflow_name' => 'Sample Workflow',
                    'is_valid' => true,
                    'errors' => [],
                    'summary' => [
                        'total_nodes' => 5,
                        'total_edges' => 4,
                        'error_count' => 0
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