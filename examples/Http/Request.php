<?php

namespace App\Examples\Http;

class Request
{
    private $method;
    private $uri;
    private $parameters;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->parameters = $_REQUEST;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getParameter($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }
    
    public function getAllParameters()
    {
        return $this->parameters;
    }
}