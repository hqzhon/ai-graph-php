<?php

namespace App\Agent\Tool;

class CalculatorTool extends BaseTool
{
    public function __construct()
    {
        parent::__construct('calculator', 'A simple calculator tool for basic arithmetic operations');
    }
    
    public function execute(array $parameters)
    {
        $operation = $parameters['operation'] ?? '';
        $a = $parameters['a'] ?? 0;
        $b = $parameters['b'] ?? 0;
        
        switch ($operation) {
            case 'add':
                return $a + $b;
            case 'subtract':
                return $a - $b;
            case 'multiply':
                return $a * $b;
            case 'divide':
                if ($b == 0) {
                    throw new \InvalidArgumentException('Division by zero');
                }
                return $a / $b;
            default:
                throw new \InvalidArgumentException('Unsupported operation: ' . $operation);
        }
    }
}