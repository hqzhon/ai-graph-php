<?php

namespace App\Agent\Monitoring;

class Debugger
{
    private $enabled = false;
    private $outputStream;
    
    public function __construct($outputStream = null)
    {
        $this->outputStream = $outputStream ?? STDOUT;
    }
    
    /**
     * 启用调试
     * 
     * @return void
     */
    public function enable(): void
    {
        $this->enabled = true;
    }
    
    /**
     * 禁用调试
     * 
     * @return void
     */
    public function disable(): void
    {
        $this->enabled = false;
    }
    
    /**
     * 记录调试信息
     * 
     * @param string $message 消息
     * @param array $context 上下文
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        if (!$this->enabled) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        fwrite($this->outputStream, "[$timestamp] DEBUG: $message$contextStr\n");
    }
    
    /**
     * 记录错误信息
     * 
     * @param string $message 消息
     * @param array $context 上下文
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        
        fwrite($this->outputStream, "[$timestamp] ERROR: $message$contextStr\n");
    }
}