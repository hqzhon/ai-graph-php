<?php

namespace App\Examples\Http;

class Response
{
    private $content;
    private $statusCode;
    private $headers;
    
    public function __construct($content = '', $statusCode = 200, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    public function getContent()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }
    
    public function send()
    {
        // 发送状态码
        http_response_code($this->statusCode);
        
        // 发送头部信息
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        // 发送内容
        echo $this->content;
    }
}