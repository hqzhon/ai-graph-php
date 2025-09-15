#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\LangGraph\Example\ExampleWorkflow;
use App\LangGraph\Example\ChatbotWorkflow;

echo "=== LangGraph PHP Implementation Test ===\n\n";

echo "1. Running Example Workflow...\n";
$finalState = ExampleWorkflow::run();
echo "Final State:\n";
print_r($finalState->getData());

echo "\n2. Running Chatbot Workflow...\n";
$finalState = ChatbotWorkflow::run('Hello, how can you help me?');
echo "Final State:\n";
print_r($finalState->getData());

echo "\n3. Running Chatbot Workflow with goodbye...\n";
$finalState = ChatbotWorkflow::run('Goodbye!');
echo "Final State:\n";
print_r($finalState->getData());

echo "\n=== Test Complete ===\n";