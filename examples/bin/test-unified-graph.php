#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\UnifiedGraph\Example\SimpleWorkflow;
use App\UnifiedGraph\Example\ExampleWorkflow as UnifiedExampleWorkflow;

echo "=== Unified Graph PHP Implementation Test ===\n\n";

echo "1. Running Simple Workflow (Graph-style)...\n";
$finalState = SimpleWorkflow::run();
echo "Final State:\n";
print_r($finalState->getData());

echo "\n2. Running Example Workflow (LangGraph-style)...\n";
$finalState = UnifiedExampleWorkflow::run();
echo "Final State:\n";
print_r($finalState->getData());

echo "\n=== Test Complete ===\n";