#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Graph\Example\SimpleWorkflow;
use App\Graph\Example\ConditionalWorkflow;

echo "=== LangGraph PHP Implementation Test ===\n\n";

echo "1. Running Simple Workflow...\n";
$finalState = SimpleWorkflow::run();
echo "Final State:\n";
print_r($finalState->getData());

echo "\n2. Running Conditional Workflow...\n";
$finalState = ConditionalWorkflow::run();
echo "Final State:\n";
print_r($finalState->getData());

echo "\n=== Test Complete ===\n";