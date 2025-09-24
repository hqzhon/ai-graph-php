<?php

namespace UnifiedGraph\Exception;

class NodeExecutionException extends LangGraphException
{
    protected $nodeKey;
    
    public function __construct(string $nodeKey, string $message, array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $context, $code, $previous);
        $this->nodeKey = $nodeKey;
    }
    
    public function getNodeKey(): string
    {
        return $this->nodeKey;
    }
}