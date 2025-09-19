<?php

namespace App\UnifiedGraph;

use App\UnifiedGraph\State\StateInterface;
use App\UnifiedGraph\Node\NodeInterface;

interface GraphInterface
{
    /**
     * 添加节点到图中
     * 
     * @param string $key 节点键名
     * @param callable|NodeInterface $action 节点执行函数或节点对象
     * @return self
     */
    public function addNode(string $key, $action): self;
    
    /**
     * 添加边到图中
     * 
     * @param string $start 起始节点
     * @param string $end 结束节点
     * @return self
     */
    public function addEdge(string $start, string $end): self;
    
    /**
     * 设置条件边
     * 
     * @param string $start 起始节点
     * @param callable $condition 条件函数
     * @param array $mapping 条件到节点的映射
     * @return self
     */
    public function addConditionalEdges(string $start, callable $condition, array $mapping): self;
    
    /**
     * 设置起始节点
     * 
     * @param string $key 节点键名
     * @return self
     */
    public function setEntryPoint(string $key): self;
    
    /**
     * 设置结束节点
     * 
     * @param string $key 节点键名
     * @return self
     */
    public function setFinishPoint(string $key): self;
    
    /**
     * 编译图
     * 
     * @return CompiledGraph
     */
    public function compile(): CompiledGraph;
}