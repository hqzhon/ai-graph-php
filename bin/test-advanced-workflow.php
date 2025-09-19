<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\UnifiedGraph\Example\AdvancedWorkflowExample;

// 运行高级工作流示例
echo "Running advanced workflow example...\n";
$finalState = AdvancedWorkflowExample::run();

echo "Final state:\n";
print_r($finalState->getData());

echo "\nState history:\n";
$history = $finalState->getHistory();
foreach ($history as $change) {
    echo "Node: {$change['node']}, Type: {$change['type']}\n";
}

echo "\nRunning workflow with interruption...\n";
AdvancedWorkflowExample::runWithInterruption();