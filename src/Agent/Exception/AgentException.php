<?php

namespace App\Agent\Exception;

class AgentException extends \Exception
{
    private $agentName;
    
    public function __construct(string $agentName, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->agentName = $agentName;
    }
    
    public function getAgentName(): string
    {
        return $this->agentName;
    }
}