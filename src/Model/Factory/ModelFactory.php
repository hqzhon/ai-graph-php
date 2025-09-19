<?php

namespace App\Model\Factory;

use App\Model\Client\ModelClientInterface;
use App\Model\Client\DeepSeekClient;
use App\Model\Client\QwenClient;

class ModelFactory
{
    private $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    /**
     * 创建模型客户端实例
     * 
     * @param string $modelType 模型类型 ('deepseek', 'qwen')
     * @return ModelClientInterface
     */
    public function createClient(string $modelType): ModelClientInterface
    {
        switch (strtolower($modelType)) {
            case 'deepseek':
                $apiKey = $this->config['deepseek_api_key'] ?? getenv('DEEPSEEK_API_KEY');
                if (!$apiKey) {
                    throw new \InvalidArgumentException('DeepSeek API key is required');
                }
                return new DeepSeekClient($apiKey);
                
            case 'qwen':
                $apiKey = $this->config['qwen_api_key'] ?? getenv('QWEN_API_KEY');
                if (!$apiKey) {
                    throw new \InvalidArgumentException('Qwen API key is required');
                }
                return new QwenClient($apiKey);
                
            default:
                // 尝试根据模型名称判断类型
                if (strpos(strtolower($modelType), 'deepseek') !== false) {
                    $apiKey = $this->config['deepseek_api_key'] ?? getenv('DEEPSEEK_API_KEY');
                    if (!$apiKey) {
                        throw new \InvalidArgumentException('DeepSeek API key is required for model: ' . $modelType);
                    }
                    $client = new DeepSeekClient($apiKey);
                    $client->setModelName($modelType);
                    return $client;
                } elseif (strpos(strtolower($modelType), 'qwen') !== false) {
                    $apiKey = $this->config['qwen_api_key'] ?? getenv('QWEN_API_KEY');
                    if (!$apiKey) {
                        throw new \InvalidArgumentException('Qwen API key is required for model: ' . $modelType);
                    }
                    $client = new QwenClient($apiKey);
                    $client->setModelName($modelType);
                    return $client;
                }
                
                throw new \InvalidArgumentException("Unsupported model type: $modelType");
        }
    }
    
    /**
     * 获取支持的模型类型列表
     * 
     * @return array
     */
    public function getSupportedModels(): array
    {
        return ['deepseek', 'qwen'];
    }
}