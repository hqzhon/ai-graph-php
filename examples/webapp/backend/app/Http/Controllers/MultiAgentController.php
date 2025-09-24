<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\StreamedResponse;

class MultiAgentController extends Controller
{
    /**
     * Stream a multi-agent workflow
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function streamWorkflow(Request $request)
    {
        try {
            // Get request data
            $workflowType = $request->input('workflow_type', 'simple');
            $task = $request->input('task', 'Research ways to improve energy efficiency in data centers');
            $deepseekKey = $request->input('deepseek_key');
            $qwenKey = $request->input('qwen_key');

            // Validate required inputs
            if (empty($task)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please provide a task description'
                ], 400);
            }

            // Validate workflow type
            if (!in_array($workflowType, ['simple', 'advanced'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid workflow type selected'
                ], 400);
            }

            // Return a streamed response
            return response()->stream(function () use ($workflowType, $task) {
                // Send initial message
                echo 'data: ' . json_encode([
                    'status' => 'started',
                    'message' => 'Workflow started'
                ]) . "\n\n";
                flush();

                // Simulate workflow steps
                $steps = [
                    'Initializing workflow...',
                    'Setting up agents...',
                    'Agents analyzing task...',
                    'Generating responses...',
                    'Compiling results...'
                ];

                foreach ($steps as $i => $step) {
                    // Simulate processing time
                    sleep(1);

                    echo 'data: ' . json_encode([
                        'status' => 'processing',
                        'step' => $step,
                        'progress' => ($i + 1) . '/' . count($steps)
                    ]) . "\n\n";
                    flush();
                }

                // Send completion message
                echo 'data: ' . json_encode([
                    'status' => 'completed',
                    'message' => 'Workflow completed successfully',
                    'result' => $this->generateSampleResult($workflowType, $task)
                ]) . "\n\n";
                flush();

                // Send finish message
                echo 'data: ' . json_encode([
                    'status' => 'finished'
                ]) . "\n\n";
                flush();
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
     * Generate a sample result for demo purposes
     *
     * @param string $workflowType
     * @param string $task
     * @return array
     */
    private function generateSampleResult($workflowType, $task)
    {
        if ($workflowType === 'simple') {
            return [
                'task' => $task,
                'summary' => 'This is a simulated result for the simple workflow. In a real implementation, this would contain the actual results from the multi-agent system.',
                'findings' => [
                    'Key finding 1',
                    'Key finding 2',
                    'Key finding 3'
                ]
            ];
        } else {
            return [
                'task' => $task,
                'executive_summary' => 'This is a simulated result for the advanced workflow. In a real implementation, this would contain detailed results from the multi-agent system with channel-based state management.',
                'detailed_analysis' => [
                    'analysis_section_1' => 'Detailed analysis content...',
                    'analysis_section_2' => 'More detailed analysis content...',
                    'analysis_section_3' => 'Even more detailed analysis content...'
                ],
                'recommendations' => [
                    'Recommendation 1',
                    'Recommendation 2',
                    'Recommendation 3'
                ]
            ];
        }
    }
}