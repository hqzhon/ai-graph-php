#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\ModelTestController;
use App\Http\Request;

echo "=== Model Test Controller Demo ===\n\n";

// 创建控制器
$controller = new ModelTestController();

// 模拟GET请求
echo "1. Testing form display...\n";
$response = $controller->showForm();
echo "Form display test completed.\n\n";

// 模拟POST请求（不带API密钥）
echo "2. Testing form processing without API keys...\n";
// 创建模拟请求对象
$request = new class extends App\Http\Request {
    public function __construct() {}
    
    public function getMethod() {
        return 'POST';
    }
    
    public function getUri() {
        return '/model-test/process';
    }
    
    public function getParameter($key, $default = null) {
        $params = [
            'deepseek_key' => '',
            'qwen_key' => '',
            'model' => 'deepseek',
            'prompt' => 'Hello, what is the capital of France?'
        ];
        return $params[$key] ?? $default;
    }
    
    public function getAllParameters() {
        return [
            'deepseek_key' => '',
            'qwen_key' => '',
            'model' => 'deepseek',
            'prompt' => 'Hello, what is the capital of France?'
        ];
    }
};

$response = $controller->processForm($request);
echo "Form processing without API keys test completed.\n\n";

echo "=== Demo Complete ===\n";
