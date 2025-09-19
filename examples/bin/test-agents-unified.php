#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Agent\AgentFactory;
use App\Agent\Example\AdvancedCollaborativeWorkflow;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

echo "=== Testing Agent Components with Unified Graph ===\n\n";

// Test 1: Create and test a simple agent
echo "1. Testing Agent Creation...\n";
$modelConfig = new ModelConfig();
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// Create a response agent
$responseAgent = $agentFactory->createResponseAgent(
    'test_agent',
    'This is a test response',
    'Test agent for verification'
);

$testTask = "Please provide a response to this test task";
$result = $responseAgent->execute($testTask);

echo "Agent response: " . $result->get('response') . "\n";
echo "Agent name: " . $responseAgent->getName() . "\n\n";

// Test 2: Run advanced collaborative workflow
echo "2. Testing Advanced Collaborative Workflow...\n";
$task = "Research ways to improve energy efficiency in data centers";
$finalState = AdvancedCollaborativeWorkflow::run($task);

echo "Workflow completed!\n";
echo "Final state keys: " . implode(', ', array_keys($finalState->getData())) . "\n";
echo "Status: " . ($finalState->get('completed') ? 'Completed' : 'Not completed') . "\n";
echo "Task: " . $finalState->get('task') . "\n";

echo "\n=== All Tests Complete ===\n";