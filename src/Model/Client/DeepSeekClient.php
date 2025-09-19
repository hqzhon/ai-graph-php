<?php

namespace App\Model\Client;

/**
 * DeepSeek API client with improved streaming capabilities
 */
class DeepSeekClient extends AbstractModelClient
{
    public function __construct(string $apiKey)
    {
        parent::__construct($apiKey, 'deepseek-chat', 'https://api.deepseek.com');
    }
    
    public function chatComplete(array $messages, array $options = []): string
    {
        $data = [
            'model' => $this->modelName,
            'messages' => $messages,
            'stream' => false
        ];
        
        // Merge additional options
        $data = array_merge($data, $options);
        
        try {
            // Send request
            $response = $this->sendRequest('/chat/completions', $data);
            
            // Check if response has errors
            if (isset($response['error'])) {
                throw new \RuntimeException('API Error: ' . $response['error']['message'] ?? 'Unknown API error');
            }
            
            // Return model response
            if (isset($response['choices'][0]['message']['content'])) {
                return $response['choices'][0]['message']['content'];
            }
            
            throw new \RuntimeException('Invalid API response format: ' . json_encode($response));
        } catch (\Exception $e) {
            throw new \RuntimeException('DeepSeek API call failed: ' . $e->getMessage());
        }
    }
    
    public function streamChatComplete(array $messages, array $options = []): \Generator
    {
        $data = [
            'model' => $this->modelName,
            'messages' => $messages,
            'stream' => true
        ];
        
        $data = array_merge($data, $options);
        
        // Use the improved streaming method from the parent class
        yield from $this->streamRequest('/chat/completions', $data);
    }
}