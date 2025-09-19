<?php

namespace App\Model\Config;

class ModelConfig
{
    private $config = [];
    
    public function __construct(array $config = [])
    {
        // 从环境变量加载默认配置
        // 在HTTP请求上下文中，使用getenv()而不是依赖$_ENV或$_SERVER
        $this->config = [
            'deepseek_api_key' => getenv('DEEPSEEK_API_KEY') ?: '',
            'qwen_api_key' => getenv('QWEN_API_KEY') ?: '',
        ];
        
        // 只合并非空的配置值
        foreach ($config as $key => $value) {
            if (!empty($value)) {
                $this->config[$key] = $value;
            }
        }
    }
    
    /**
     * 获取配置值
     * 
     * @param string $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
    
    /**
     * 设置配置值
     * 
     * @param string $key 配置键
     * @param mixed $value 配置值
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }
    
    /**
     * 获取所有配置
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }
    
    /**
     * 从文件加载配置
     * 
     * @param string $filePath 配置文件路径
     * @return self
     */
    public static function fromFile(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Config file not found: $filePath");
        }
        
        $config = require $filePath;
        
        if (!is_array($config)) {
            throw new \InvalidArgumentException("Config file must return an array");
        }
        
        return new self($config);
    }
}