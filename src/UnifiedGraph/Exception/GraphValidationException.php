<?php

namespace UnifiedGraph\Exception;

/**
 * Exception thrown when graph validation fails
 */
class GraphValidationException extends \Exception
{
    private array $context;

    public function __construct(string $message, array $context = [], int $code = 0, \Throwable $previous = null)
    {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }
}