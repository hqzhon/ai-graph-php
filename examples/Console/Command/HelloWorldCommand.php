<?php

namespace App\Examples\Console\Command;

use App\Examples\Console\Command as BaseCommand;

class HelloWorldCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('hello', 'Prints Hello World');
    }
    
    public function execute($args)
    {
        echo "Hello, World!\n";
    }
}