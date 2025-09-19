#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Client\DeepSeekClient;
use App\Model\Client\QwenClient;

echo "=== Model Client Test ===\n\n";

// 测试DeepSeek客户端
echo "1. Testing DeepSeek client...\n";
try {
    $deepseekClient = new DeepSeekClient('test-key');
    echo "DeepSeek client created successfully.\n";
    echo "Model name: " . $deepseekClient->getModelName() . "\n\n";
} catch (Exception $e) {
    echo "DeepSeek client error: " . $e->getMessage() . "\n\n";
}

// 测试Qwen客户端
echo "2. Testing Qwen client...\n";
try {
    $qwenClient = new QwenClient('test-key');
    echo "Qwen client created successfully.\n";
    echo "Model name: " . $qwenClient->getModelName() . "\n\n";
} catch (Exception $e) {
    echo "Qwen client error: " . $e->getMessage() . "\n\n";
}

echo "=== Test Complete ===\n";