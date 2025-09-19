<?php

namespace App\Examples\Console;

use Psr\Container\ContainerInterface;

class Application
{
    private $commandMap = [];
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addCommand(string $commandClass): void
    {
        if (!is_subclass_of($commandClass, Command::class)) {
            throw new \InvalidArgumentException("Class {$commandClass} is not a valid command.");
        }
        
        // We need an instance to get the name, but we don't store it.
        // This assumes commands have no constructor dependencies or they are resolvable by the container.
        /** @var Command $command */
        $command = $this->container->get($commandClass);
        $this->commandMap[$command->getName()] = $commandClass;
    }

    public function run($argv): void
    {
        if (count($argv) < 2) {
            $this->showHelp();
            return;
        }

        $commandName = $argv[1];
        $args = array_slice($argv, 2);

        if (!isset($this->commandMap[$commandName])) {
            echo "Command not found: $commandName\n";
            $this->showHelp();
            return;
        }

        $commandClass = $this->commandMap[$commandName];
        $command = $this->container->get($commandClass);
        $command->execute($args);
    }

    private function showHelp(): void
    {
        echo "Available commands:\n";
        foreach ($this->commandMap as $name => $class) {
            /** @var Command $command */
            $command = $this->container->get($class);
            echo "  " . $name . " - " . $command->getDescription() . "\n";
        }
    }
}