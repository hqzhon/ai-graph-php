<?php

// Test script to verify that the Laravel backend can access the main SDK

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;

echo "Testing LangGraph SDK access from Laravel backend...\n";

try {
    // Create a simple workflow
    $graph = new StateGraph(State::class);
    
    // Add nodes
    $graph->addNode('start', function ($state) {
        return ['step' => 'start', 'message' => 'Workflow started'];
    });
    
    $graph->addNode('end', function ($state) {
        return ['step' => 'end', 'message' => 'Workflow completed'];
    });
    
    // Add edge
    $graph->addEdge('start', 'end');
    
    // Set entry and finish points
    $graph->setEntryPoint('start');
    $graph->setFinishPoint('end');
    
    // Compile and execute
    $compiled = $graph->compile();
    $initialState = new State(['test' => 'laravel_integration']);
    $finalState = $compiled->execute($initialState);
    
    echo "SUCCESS: LangGraph SDK is accessible from Laravel backend\n";
    echo "Final state: " . json_encode($finalState->getData()) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}