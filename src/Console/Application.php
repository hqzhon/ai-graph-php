<?php

namespace App\Console;

class Application
{
    private $commands = [];
    
    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }
    
    public function run($argv)
    {
        // 如果没有参数，显示帮助信息
        if (count($argv) < 2) {
            $this->showHelp();
            return;
        }
        
        $commandName = $argv[1];
        $args = array_slice($argv, 2);
        
        // 检查命令是否存在
        if (!isset($this->commands[$commandName])) {
            echo "Command not found: $commandName\n";
            $this->showHelp();
            return;
        }
        
        // 执行命令
        $this->commands[$commandName]->execute($args);
    }
    
    private function showHelp()
    {
        echo "Available commands:\n";
        foreach ($this->commands as $command) {
            echo "  " . $command->getName() . " - " . $command->getDescription() . "\n";
        }
    }
}