<?php

namespace App\Model\Client;

abstract class AbstractModelClient implements ModelClientInterface
{
    protected $apiKey;
    protected $modelName;
    protected $baseUrl;
    
    public function __construct(string $apiKey, string $modelName, string $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->modelName = $modelName;
        $this->baseUrl = $baseUrl;
    }
    
    public function getModelName(): string
    {
        return $this->modelName;
    }
    
    /**
     * 发送HTTP请求
     * 
     * @param string $endpoint API端点
     * @param array $data 请求数据
     * @return array 响应数据
     */
    protected function sendRequest(string $endpoint, array $data): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $options = [
            'http' => [
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey
                ],
                'method' => 'POST',
                'content' => json_encode($data),
                'timeout' => 30
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === false) {
            throw new \RuntimeException('Failed to send request to ' . $url);
        }
        
        $response = json_decode($result, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse JSON response: ' . json_last_error_msg());
        }
        
        return $response;
    }
}