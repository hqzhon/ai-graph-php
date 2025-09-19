<?php

namespace App\Model\Client;

/**
 * Abstract base class for model clients with improved streaming capabilities
 */
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
    
    public function setModelName(string $modelName): void
    {
        $this->modelName = $modelName;
    }
    
    /**
     * Sends an HTTP request to the API
     * 
     * @param string $endpoint API endpoint
     * @param array $data Request data
     * @return array Response data
     */
    protected function sendRequest(string $endpoint, array $data): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $jsonData = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to encode request data: ' . json_last_error_msg());
        }
        
        $options = [
            'http' => [
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Length: ' . strlen($jsonData)
                ],
                'method' => 'POST',
                'content' => $jsonData,
                'timeout' => 60,
                'ignore_errors' => true
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        // Get HTTP response status code
        $httpStatus = 500;
        if (isset($http_response_header)) {
            $httpStatusLine = $http_response_header[0];
            preg_match('/HTTP\/\d\.\d\s+(\d+)/', $httpStatusLine, $matches);
            $httpStatus = intval($matches[1] ?? 500);
        }
        
        if ($result === false) {
            $error = error_get_last();
            throw new \RuntimeException('Failed to send request to ' . $url . '. HTTP Status: ' . $httpStatus . '. Error: ' . ($error['message'] ?? 'Unknown error'));
        }
        
        // Handle HTTP error status
        if ($httpStatus >= 400) {
            throw new \RuntimeException('HTTP Error ' . $httpStatus . ': ' . substr($result, 0, 500));
        }
        
        // Handle possible BOM markers
        $result = trim($result, "\xef\xbb\xbf");
        
        // If response is empty
        if (empty($result)) {
            throw new \RuntimeException('Empty response from API. HTTP Status: ' . $httpStatus);
        }
        
        $response = json_decode($result, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // If JSON parsing fails, return more detailed error information
            $preview = substr($result, 0, 1000);
            throw new \RuntimeException(
                'Failed to parse JSON response: ' . json_last_error_msg() . 
                '. HTTP Status: ' . $httpStatus . 
                '. Response preview: ' . $preview .
                '. Response length: ' . strlen($result)
            );
        }
        
        return $response;
    }
    
    /**
     * Improved streaming method that yields character by character for逐字流输出
     * 
     * @param array $messages Message array
     * @param array $options Additional options
     * @return \Generator Character by character streaming output
     */
    protected function streamRequest(string $endpoint, array $data): \Generator
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $jsonData = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to encode request data: ' . json_last_error_msg());
        }
        
        $contextOptions = [
            'http' => [
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey
                ],
                'method' => 'POST',
                'content' => $jsonData,
                'timeout' => 60
            ]
        ];
        
        $context = stream_context_create($contextOptions);
        $stream = @fopen($url, 'r', false, $context);
        
        if ($stream === false) {
            $error = error_get_last();
            throw new \RuntimeException('Failed to open stream to ' . $url . '. Error: ' . ($error['message'] ?? 'Unknown error'));
        }
        
        // Buffer to accumulate content for character-by-character streaming
        $buffer = '';
        
        while (!feof($stream)) {
            $line = fgets($stream);
            if ($line === false) {
                break;
            }
            
            $line = trim($line);
            if (strpos($line, 'data: ') === 0) {
                $jsonData = substr($line, 6);
                if ($jsonData === '[DONE]') {
                    break;
                }
                
                $chunk = json_decode($jsonData, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($chunk['choices'][0]['delta']['content'])) {
                    $content = $chunk['choices'][0]['delta']['content'];
                    
                    // Yield character by character for逐字流输出
                    for ($i = 0; $i < mb_strlen($content, 'UTF-8'); $i++) {
                        $char = mb_substr($content, $i, 1, 'UTF-8');
                        yield $char;
                    }
                }
            }
        }
        
        fclose($stream);
    }
}