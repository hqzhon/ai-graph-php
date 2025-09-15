<?php

namespace App\Logger;

class Logger
{
    private $logFile;
    
    public function __construct($logFile)
    {
        $this->logFile = $logFile;
    }
    
    public function info($message)
    {
        $this->log('INFO', $message);
    }
    
    public function error($message)
    {
        $this->log('ERROR', $message);
    }
    
    private function log($level, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}