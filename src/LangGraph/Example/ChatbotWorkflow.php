<?php

namespace App\LangGraph\Example;

use App\LangGraph\StateGraph;
use App\LangGraph\State\GraphState;

class ChatbotWorkflow
{
    public static function create(): StateGraph
    {
        $graph = new StateGraph(GraphState::class);
        
        // 添加节点
        $graph->addNode('receive_input', function ($state) {
            return [
                'step' => 'receive_input',
                'message' => 'Received user input: ' . ($state['input'] ?? 'Hello')
            ];
        });
        
        $graph->addNode('analyze_intent', function ($state) {
            // 模拟意图分析
            $input = $state['input'] ?? '';
            $intent = 'greeting';
            
            if (strpos($input, 'help') !== false) {
                $intent = 'help_request';
            } elseif (strpos($input, 'bye') !== false) {
                $intent = 'goodbye';
            }
            
            return [
                'step' => 'analyze_intent',
                'intent' => $intent,
                'message' => 'Analyzed intent: ' . $intent
            ];
        });
        
        $graph->addNode('generate_response', function ($state) {
            $intent = $state['intent'] ?? 'greeting';
            $responses = [
                'greeting' => 'Hello! How can I help you today?',
                'help_request' => 'I\'d be happy to help you with that!',
                'goodbye' => 'Goodbye! Have a great day!'
            ];
            
            $response = $responses[$intent] ?? 'I\'m not sure how to respond to that.';
            
            return [
                'step' => 'generate_response',
                'response' => $response,
                'message' => 'Generated response: ' . $response
            ];
        });
        
        $graph->addNode('end_conversation', function ($state) {
            return [
                'step' => 'end_conversation',
                'ended' => true,
                'message' => 'Conversation ended'
            ];
        });
        
        // 添加边
        $graph->addEdge('receive_input', 'analyze_intent');
        $graph->addEdge('analyze_intent', 'generate_response');
        
        // 添加条件边
        $graph->addConditionalEdges('generate_response', function ($state) {
            $intent = $state['intent'] ?? 'greeting';
            return $intent === 'goodbye' ? 'end' : 'continue';
        }, [
            'end' => 'end_conversation',
            'continue' => 'end_conversation' // 修改为直接结束，而不是循环
        ]);
        
        // 设置起始和结束节点
        $graph->setEntryPoint('receive_input');
        $graph->setFinishPoint('end_conversation');
        
        return $graph;
    }
    
    public static function run(string $input = 'Hello'): GraphState
    {
        // 创建图
        $graph = self::create();
        
        // 编译图
        $compiled = $graph->compile();
        
        // 创建初始状态
        $initialState = new GraphState([
            'workflow' => 'chatbot',
            'input' => $input
        ]);
        
        // 执行图
        $finalState = $compiled->execute($initialState);
        
        return $finalState;
    }
}