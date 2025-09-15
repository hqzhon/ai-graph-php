<?php

namespace App\Graph\Executor;

use App\Graph\Node\NodeInterface;
use App\Graph\Edge\EdgeInterface;
use App\Graph\State\StateInterface;
use App\Graph\State\State;

class Executor implements ExecutorInterface
{
    protected $nodes = [];
    protected $edges = [];
    protected $startNode = null;
    
    public function addNode(NodeInterface $node): void
    {
        $this->nodes[$node->getName()] = $node;
    }
    
    public function addEdge(EdgeInterface $edge): void
    {
        $this->edges[] = $edge;
    }
    
    public function setStartNode(string $nodeName): void
    {
        if (!isset($this->nodes[$nodeName])) {
            throw new \InvalidArgumentException("Node '$nodeName' not found");
        }
        
        $this->startNode = $nodeName;
    }
    
    public function execute(StateInterface $state): StateInterface
    {
        if ($this->startNode === null) {
            throw new \RuntimeException("Start node not set");
        }
        
        $currentNodeName = $this->startNode;
        $visitedNodes = [];
        
        // 执行工作流直到没有可执行的边
        while ($currentNodeName !== null) {
            // 检查是否访问过该节点（防止无限循环）
            if (in_array($currentNodeName, $visitedNodes)) {
                throw new \RuntimeException("Cycle detected at node '$currentNodeName'");
            }
            
            $visitedNodes[] = $currentNodeName;
            
            // 获取当前节点
            if (!isset($this->nodes[$currentNodeName])) {
                throw new \RuntimeException("Node '$currentNodeName' not found");
            }
            
            $node = $this->nodes[$currentNodeName];
            
            // 执行节点
            $stateData = $node->execute($state->getData());
            $state->setData($stateData);
            
            // 查找下一个节点
            $currentNodeName = $this->getNextNode($currentNodeName, $state);
        }
        
        return $state;
    }
    
    /**
     * 获取下一个要执行的节点
     * 
     * @param string $currentNodeName 当前节点名称
     * @param StateInterface $state 当前状态
     * @return string|null 下一个节点名称，如果没有则返回null
     */
    protected function getNextNode(string $currentNodeName, StateInterface $state): ?string
    {
        // 查找从当前节点出发的边
        foreach ($this->edges as $edge) {
            if ($edge->getFrom() === $currentNodeName && $edge->canActivate($state->getData())) {
                return $edge->getTo();
            }
        }
        
        // 没有找到可激活的边，返回null表示结束
        return null;
    }
}