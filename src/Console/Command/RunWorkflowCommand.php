<?php

namespace App\Console\Command;

use App\Console\Command as BaseCommand;
use App\Graph\Example\SimpleWorkflow;
use App\Graph\Example\ConditionalWorkflow;

class RunWorkflowCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('workflow:run', 'Run a sample workflow');
    }
    
    public function execute($args)
    {
        echo "Running Simple Workflow...\n";
        
        // 运行简单工作流
        $finalState = SimpleWorkflow::run();
        
        echo "Simple Workflow Final State:\n";
        print_r($finalState->getData());
        
        echo "\nRunning Conditional Workflow...\n";
        
        // 运行条件工作流
        $finalState = ConditionalWorkflow::run();
        
        echo "Conditional Workflow Final State:\n";
        print_r($finalState->getData());
    }
}