#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Agent\Example\AdvancedCollaborativeWorkflow;
use App\Agent\Example\SwarmIntelligenceExample;

echo "=== Advanced Collaborative AI System Demo ===\n\n";

// 运行高级协作工作流
echo "1. Running advanced collaborative workflow...\n";
try {
    $finalState = AdvancedCollaborativeWorkflow::run(
        'Research and develop a solution for reducing energy consumption in data centers'
    );
    echo "Workflow completed successfully.\n";
    echo "Final state keys: " . implode(', ', array_keys($finalState->getData())) . "\n\n";
} catch (Exception $e) {
    echo "Error in workflow: " . $e->getMessage() . "\n\n";
}

// 运行群体智能示例
echo "2. Running swarm intelligence example...\n";
try {
    $result = SwarmIntelligenceExample::run();
    echo "Swarm intelligence simulation completed.\n";
    echo "Performance metrics:\n";
    foreach ($result['performance_metrics'] as $metric => $value) {
        echo "  $metric: " . (is_float($value) ? number_format($value, 4) : $value) . "\n";
    }
    echo "Optimized solution: " . ($result['optimized_solution'] ?? 'N/A') . "\n\n";
} catch (Exception $e) {
    echo "Error in swarm intelligence: " . $e->getMessage() . "\n\n";
}

echo "=== Demo Complete ===\n";
