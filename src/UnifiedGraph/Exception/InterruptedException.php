<?php

namespace App\UnifiedGraph\Exception;

class InterruptedException extends LangGraphException
{
    protected $nodeKey;
    protected $interruptType; // before or after
    
    public function __construct(string $nodeKey, string $interruptType, string $message, array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $context, $code, $previous);
        $this->nodeKey = $nodeKey;
        $this->interruptType = $interruptType;
    }
    
    public function getNodeKey(): string
    {
        return $this->nodeKey;
    }
    
    public function getInterruptType(): string
    {
        return $this->interruptType;
    }
}