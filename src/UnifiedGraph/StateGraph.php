<?php

namespace UnifiedGraph;

use UnifiedGraph\State\StateInterface;
use UnifiedGraph\Node\NodeInterface;
use UnifiedGraph\Checkpoint\CheckpointSaverInterface;
use UnifiedGraph\Exception\GraphValidationException;
use UnifiedGraph\CompiledGraph;

class StateGraph extends BaseGraph
{
    private $stateClass;
    private $channels = [];
    private $checkpointSaver = null;
    
    public function __construct(string $stateClass)
    {
        $this->stateClass = $stateClass;
    }
    
    public function getStateClass(): string
    {
        return $this->stateClass;
    }
    
    /**
     * 添加通道定义
     * 
     * @param string $key 通道键名
     * @param mixed $channelDef 通道定义
     * @return self
     */
    public function addChannel(string $key, $channelDef): self
    {
        $this->channels[$key] = $channelDef;
        return $this;
    }
    
    /**
     * 批量添加通道定义
     * 
     * @param array $channels 通道定义数组
     * @return self
     */
    public function addChannels(array $channels): self
    {
        foreach ($channels as $key => $channelDef) {
            $this->addChannel($key, $channelDef);
        }
        return $this;
    }
    
    /**
     * 获取通道定义
     * 
     * @return array
     */
    public function getChannels(): array
    {
        return $this->channels;
    }
    
    /**
     * 设置检查点保存器
     * 
     * @param CheckpointSaverInterface $checkpointSaver
     * @return self
     */
    public function setCheckpointSaver(CheckpointSaverInterface $checkpointSaver): self
    {
        $this->checkpointSaver = $checkpointSaver;
        return $this;
    }
    
    public function addStreamNode(string $key, callable $action): self
    {
        $this->streamNodes[$key] = $action;
        return $this;
    }

    public function compile(): CompiledGraph
    {
        // 验证图的完整性
        $this->validateGraph();
        
        // 创建编译后的图
        return new CompiledGraph(
            $this->nodes,
            $this->streamNodes, // Pass stream nodes
            $this->edges,
            $this->conditionalEdges,
            $this->entryPoint,
            $this->finishPoints,
            $this->stateClass,
            $this->channels, // Pass channels
            $this->checkpointSaver // Pass checkpoint saver
        );
    }
    
    protected function validateGraph(): void
    {
        parent::validateGraph();
    }
}