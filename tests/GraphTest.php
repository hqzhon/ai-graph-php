<?php

namespace App\Tests\Graph;

use App\Graph\Executor\Executor;
use App\Graph\Node\StartNode;
use App\Graph\Node\ProcessNode;
use App\Graph\Node\EndNode;
use App\Graph\Edge\Edge;
use App\Graph\State\State;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase
{
    public function testSimpleWorkflow()
    {
        // 创建执行器
        $executor = new Executor();
        
        // 添加节点
        $startNode = new StartNode('start');
        $processNode = new ProcessNode('process');
        $endNode = new EndNode('end');
        
        $executor->addNode($startNode);
        $executor->addNode($processNode);
        $executor->addNode($endNode);
        
        // 添加边
        $executor->addEdge(new Edge('start', 'process'));
        $executor->addEdge(new Edge('process', 'end'));
        
        // 设置起始节点
        $executor->setStartNode('start');
        
        // 创建初始状态
        $initialState = new State([
            'test' => 'value'
        ]);
        
        // 执行工作流
        $finalState = $executor->execute($initialState);
        
        // 验证最终状态
        $data = $finalState->getData();
        $this->assertEquals('end', $data['step']);
        $this->assertEquals('Workflow completed', $data['message']);
    }
    
    public function testStateManagement()
    {
        $state = new State(['key1' => 'value1']);
        
        // 测试获取值
        $this->assertEquals('value1', $state->get('key1'));
        $this->assertEquals('default', $state->get('nonexistent', 'default'));
        
        // 测试设置值
        $state->set('key2', 'value2');
        $this->assertEquals('value2', $state->get('key2'));
        
        // 测试合并数据
        $state->merge(['key3' => 'value3']);
        $this->assertEquals('value3', $state->get('key3'));
        
        // 测试检查键是否存在
        $this->assertTrue($state->has('key1'));
        $this->assertFalse($state->has('nonexistent'));
    }
}