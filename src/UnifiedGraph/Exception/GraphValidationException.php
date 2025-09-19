<?php

namespace App\UnifiedGraph\Exception;

class GraphValidationException extends LangGraphException
{
    public function __construct(string $message, array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $context, $code, $previous);
    }
}