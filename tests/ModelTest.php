<?php

namespace App\Tests\Model;

use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testModelFactoryCreation()
    {
        // 创建配置
        $config = new ModelConfig([
            'deepseek_api_key' => 'test_key',
            'qwen_api_key' => 'test_key'
        ]);
        
        // 创建工厂
        $factory = new ModelFactory($config->all());
        
        // 测试创建DeepSeek客户端
        $deepseekClient = $factory->createClient('deepseek');
        $this->assertInstanceOf(\App\Model\Client\DeepSeekClient::class, $deepseekClient);
        
        // 测试创建Qwen客户端
        $qwenClient = $factory->createClient('qwen');
        $this->assertInstanceOf(\App\Model\Client\QwenClient::class, $qwenClient);
        
        // 测试支持的模型列表
        $supportedModels = $factory->getSupportedModels();
        $this->assertContains('deepseek', $supportedModels);
        $this->assertContains('qwen', $supportedModels);
    }
    
    public function testModelFactoryWithInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // 创建配置
        $config = new ModelConfig([
            'deepseek_api_key' => 'test_key',
            'qwen_api_key' => 'test_key'
        ]);
        
        // 创建工厂
        $factory = new ModelFactory($config->all());
        
        // 尝试创建不支持的模型类型
        $factory->createClient('unsupported');
    }
    
    public function testConfigManagement()
    {
        $config = new ModelConfig([
            'deepseek_api_key' => 'test_key_1',
            'qwen_api_key' => 'test_key_2'
        ]);
        
        // 测试获取配置
        $this->assertEquals('test_key_1', $config->get('deepseek_api_key'));
        $this->assertEquals('test_key_2', $config->get('qwen_api_key'));
        
        // 测试默认值
        $this->assertEquals('default', $config->get('nonexistent', 'default'));
        
        // 测试设置配置
        $config->set('new_key', 'new_value');
        $this->assertEquals('new_value', $config->get('new_key'));
        
        // 测试获取所有配置
        $allConfig = $config->all();
        $this->assertArrayHasKey('deepseek_api_key', $allConfig);
        $this->assertArrayHasKey('qwen_api_key', $allConfig);
    }
}