<?php

namespace App\Container;

class Container
{
    private $services = [];
    private $instances = [];
    
    public function set($name, $service)
    {
        $this->services[$name] = $service;
        unset($this->instances[$name]); // 如果已存在实例，清除它
    }
    
    public function get($name)
    {
        // 如果已经有实例，直接返回
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }
        
        // 如果没有服务定义，抛出异常
        if (!isset($this->services[$name])) {
            throw new \Exception("Service not found: $name");
        }
        
        // 获取服务定义
        $service = $this->services[$name];
        
        // 如果是可调用的（函数/闭包），执行它来创建实例
        if (is_callable($service)) {
            $instance = call_user_func($service, $this);
            $this->instances[$name] = $instance;
            return $instance;
        }
        
        // 如果是类名，直接创建实例
        if (is_string($service)) {
            $instance = new $service();
            $this->instances[$name] = $instance;
            return $instance;
        }
        
        // 其他情况，直接返回
        return $service;
    }
}