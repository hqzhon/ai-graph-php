<?php

namespace App\Graph\Executor;

use App\Graph\Node\NodeInterface;
use App\Graph\Edge\EdgeInterface;
use App\Graph\State\StateInterface;

interface ExecutorInterface
{
    /**
     * 添加节点到执行器
     * 
     * @param NodeInterface $node 节点
     * @return void
     */
    public function addNode(NodeInterface $node): void;
    
    /**
     * 添加边到执行器
     * 
     * @param EdgeInterface $edge 边
     * @return void
     */
    public function addEdge(EdgeInterface $edge): void;
    
    /**
     * 设置起始节点
     * 
     * @param string $nodeName 节点名称
     * @return void
     */
    public function setStartNode(string $nodeName): void;
    
    /**
     * 执行工作流
     * 
     * @param StateInterface $state 初始状态
     * @return StateInterface 最终状态
     */
    public function execute(StateInterface $state): StateInterface;
}