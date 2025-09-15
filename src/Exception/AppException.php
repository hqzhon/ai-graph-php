<?php

namespace App\Exception;

class AppException extends \Exception
{
    protected $statusCode;
    
    public function __construct($message = "", $statusCode = 500, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, 0, $previous);
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}