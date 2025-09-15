<?php

namespace App\Model\Client;

class QwenClient extends AbstractModelClient
{
    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey, 'qwen-plus', 'https://dashscope.aliyuncs.com/compatible-mode/v1');
    }
    
    public function chatComplete(array $messages, array $options = []): string
    {
        $data = [
            'model' => $this->modelName,
            'messages' => $messages,
            'stream' => false
        ];
        
        // 合并额外选项
        $data = array_merge($data, $options);
        
        // 发送请求
        $response = $this->sendRequest('/chat/completions', $data);
        
        // 检查响应是否有错误
        if (isset($response['error'])) {
            throw new \RuntimeException('API Error: ' . $response['error']['message']);
        }
        
        // 返回模型响应
        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }
        
        throw new \RuntimeException('Invalid API response format');
    }
}