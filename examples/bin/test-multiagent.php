#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Agent\Example\MultiAgentWorkflow;

echo "=== Multi-Agent System Demo ===\n\n";

// 运行多智能体工作流
echo "Running multi-agent workflow...\n";
try {
    $finalState = MultiAgentWorkflow::run('Calculate the sum of 10 and 20, then multiply by 5');
    echo "Final State:\n";
    print_r($finalState->getData());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Demo Complete ===\n";