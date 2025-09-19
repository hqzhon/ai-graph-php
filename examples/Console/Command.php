<?php

namespace App\Examples\Console;

class Command
{
    protected $name;
    protected $description;
    
    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function execute($args)
    {
        // 子类应该重写这个方法
        echo "Command executed: " . $this->name . "\n";
    }
}