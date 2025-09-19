<?php

namespace App\UnifiedGraph\State;

class ChannelsState implements StateInterface
{
    /**
     * @var ChannelInterface[]
     */
    protected $channels = [];
    
    /**
     * @var array
     */
    protected $channelDefs = [];
    
    /**
     * @var StateTracker
     */
    protected $tracker;
    
    /**
     * @var bool
     */
    protected $trackChanges = true;
    
    public function __construct(array $channelDefs, array $initialData = [], bool $trackChanges = true)
    {
        $this->channelDefs = $channelDefs;
        $this->trackChanges = $trackChanges;
        if ($trackChanges) {
            $this->tracker = new StateTracker();
        }
        $this->initializeChannels($initialData);
    }
    
    protected function initializeChannels(array $initialData = []): void
    {
        foreach ($this->channelDefs as $key => $channelDef) {
            if (is_array($channelDef) && isset($channelDef['type'])) {
                // 复杂通道定义
                $type = $channelDef['type'];
                $initialValue = $initialData[$key] ?? $channelDef['default'] ?? null;
                
                switch ($type) {
                    case 'last_value':
                        $this->channels[$key] = new LastValueChannel($key, $initialValue);
                        break;
                    case 'binary_operator':
                        $operator = $channelDef['operator'] ?? function ($a, $b) { return $b; };
                        $this->channels[$key] = new BinaryOperatorAggregateChannel($key, $operator, $initialValue);
                        break;
                    default:
                        // 默认使用LastValueChannel
                        $this->channels[$key] = new LastValueChannel($key, $initialValue);
                }
            } else {
                // 简单通道定义，使用默认的LastValueChannel
                $initialValue = $initialData[$key] ?? null;
                $this->channels[$key] = new LastValueChannel($key, $initialValue);
            }
        }
    }
    
    public function getData(): array
    {
        $data = [];
        foreach ($this->channels as $channel) {
            $data[$channel->getKey()] = $channel->get();
        }
        return $data;
    }
    
    public function setData(array $data): void
    {
        $before = null;
        if ($this->trackChanges) {
            $before = $this->getData();
        }
        
        foreach ($data as $key => $value) {
            if (isset($this->channels[$key])) {
                $this->channels[$key]->update($value);
            }
        }
        
        if ($this->trackChanges && $before) {
            $after = $this->getData();
            $this->tracker->recordChange('setData', $before, $after, 'setData');
        }
    }
    
    public function get(string $key, $default = null)
    {
        if (isset($this->channels[$key])) {
            return $this->channels[$key]->get();
        }
        return $default;
    }
    
    public function set(string $key, $value): void
    {
        if (isset($this->channels[$key])) {
            $before = null;
            if ($this->trackChanges) {
                $before = $this->getData();
            }
            
            $this->channels[$key]->update($value);
            
            if ($this->trackChanges && $before) {
                $after = $this->getData();
                $this->tracker->recordChange("set_{$key}", $before, $after, 'set');
            }
        }
    }
    
    public function has(string $key): bool
    {
        return isset($this->channels[$key]);
    }
    
    public function merge(array $data): void
    {
        $before = null;
        if ($this->trackChanges) {
            $before = $this->getData();
        }
        
        foreach ($data as $key => $value) {
            if (isset($this->channels[$key])) {
                $this->channels[$key]->merge($value);
            }
        }
        
        if ($this->trackChanges && $before) {
            $after = $this->getData();
            $this->tracker->recordChange('merge', $before, $after, 'merge');
        }
    }
    
    /**
     * 获取通道定义
     * 
     * @return array
     */
    public function getChannelDefs(): array
    {
        return $this->channelDefs;
    }
    
    /**
     * 获取特定通道
     * 
     * @param string $key
     * @return ChannelInterface|null
     */
    public function getChannel(string $key): ?ChannelInterface
    {
        return $this->channels[$key] ?? null;
    }
    
    /**
     * 获取状态追踪器
     * 
     * @return StateTracker|null
     */
    public function getTracker(): ?StateTracker
    {
        return $this->tracker;
    }
    
    /**
     * 获取状态变更历史
     * 
     * @return array
     */
    public function getHistory(): array
    {
        return $this->tracker ? $this->tracker->getHistory() : [];
    }
    
    /**
     * 设置是否追踪变更
     * 
     * @param bool $trackChanges
     * @return void
     */
    public function setTrackChanges(bool $trackChanges): void
    {
        $this->trackChanges = $trackChanges;
        if ($trackChanges && !$this->tracker) {
            $this->tracker = new StateTracker();
        }
    }
}