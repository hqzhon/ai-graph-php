<?php

namespace App\Agent;

use App\UnifiedGraph\State\State;
use App\Model\Client\ModelClientInterface;

class ModelBasedAgent extends BaseAgent
{
    protected $modelClient;
    protected $systemPrompt;
    
    public function __construct(
        string $name,
        ModelClientInterface $modelClient,
        string $systemPrompt = "You are a helpful AI assistant."
    ) {
        parent::__construct($name);
        $this->modelClient = $modelClient;
        $this->systemPrompt = $systemPrompt;
    }
    
    public function streamExecute(string $task, ?State $context = null): \Generator
    {
        $contextData = $this->getContext($context);
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt]
        ];
        if (isset($contextData['history']) && is_array($contextData['history'])) {
            foreach ($contextData['history'] as $message) {
                $messages[] = $message;
            }
        }
        $messages[] = ['role' => 'user', 'content' => $task];

        $stream = $this->modelClient->streamChatComplete($messages);

        $fullResponse = '';
        foreach ($stream as $chunk) {
            $fullResponse .= $chunk;
            $newState = new State($contextData);
            $newState->merge([
                'agent' => $this->name,
                'task' => $task,
                'response' => $fullResponse, // Yielding the aggregated response so far
            ]);
            yield $newState;
        }

        // Final update after stream is complete
        $this->updateMemory([
            'last_task' => $task,
            'last_response' => $fullResponse
        ]);

        $finalState = new State($contextData);
        $finalState->merge([
            'agent' => $this->name,
            'task' => $task,
            'response' => $fullResponse,
            'history' => array_merge(
                $contextData['history'] ?? [],
                [
                    ['role' => 'user', 'content' => $task],
                    ['role' => 'assistant', 'content' => $fullResponse]
                ]
            )
        ]);
        yield $finalState;
    }
}
}