<?php

namespace App\UnifiedGraph\Executor;

use App\UnifiedGraph\Node\NodeInterface;
use App\UnifiedGraph\Edge\EdgeInterface;
use App\UnifiedGraph\State\StateInterface;
use App\UnifiedGraph\State\State;

class Executor
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
    
    public function stream(StateInterface $state): \Generator
    {
        if ($this->startNode === null) {
            throw new \RuntimeException("Start node not set");
        }

        $currentNodeName = $this->startNode;
        $visitedNodes = [];

        while ($currentNodeName !== null) {
            if (in_array($currentNodeName, $visitedNodes)) {
                throw new \RuntimeException("Cycle detected at node '$currentNodeName'");
            }
            $visitedNodes[] = $currentNodeName;

            if (!isset($this->nodes[$currentNodeName])) {
                throw new \RuntimeException("Node '$currentNodeName' not found");
            }

            $node = $this->nodes[$currentNodeName];

            // Check if the node has a streamExecute method
            if (method_exists($node, 'streamExecute')) {
                yield from $node->streamExecute($state->getData());
                // After streaming, we need to get the final state to continue evaluation
                $lastStateData = null;
                foreach ($node->streamExecute($state->getData()) as $streamedState) {
                    $lastStateData = $streamedState->getData();
                }
                $state->setData($lastStateData);

            } else {
                $stateData = $node->execute($state->getData());
                $state->setData($stateData);
                yield $state;
            }

            $currentNodeName = $this->getNextNode($currentNodeName, $state);
        }
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