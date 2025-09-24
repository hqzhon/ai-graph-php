<?php

namespace UnifiedGraph\State;

class StateTracker
{
    /**
     * @var array
     */
    protected $history = [];
    
    /**
     * @var int
     */
    protected $maxHistory = 100;
    
    /**
     * 记录状态变更
     * 
     * @param string $node 节点名称
     * @param array $before 变更前的状态
     * @param array $after 变更后的状态
     * @param string $type 变更类型
     * @return void
     */
    public function recordChange(string $node, array $before, array $after, string $type = 'update'): void
    {
        $change = [
            'node' => $node,
            'type' => $type,
            'timestamp' => microtime(true),
            'before' => $before,
            'after' => $after,
            'diff' => $this->computeDiff($before, $after)
        ];
        
        $this->history[] = $change;
        
        // 限制历史记录数量
        if (count($this->history) > $this->maxHistory) {
            array_shift($this->history);
        }
    }
    
    /**
     * 计算状态差异
     * 
     * @param array $before
     * @param array $after
     * @return array
     */
    protected function computeDiff(array $before, array $after): array
    {
        $diff = [];
        
        // 找出新增和修改的键
        foreach ($after as $key => $value) {
            if (!array_key_exists($key, $before)) {
                $diff[$key] = ['type' => 'added', 'value' => $value];
            } elseif ($before[$key] !== $value) {
                $diff[$key] = [
                    'type' => 'modified', 
                    'before' => $before[$key], 
                    'after' => $value
                ];
            }
        }
        
        // 找出删除的键
        foreach ($before as $key => $value) {
            if (!array_key_exists($key, $after)) {
                $diff[$key] = ['type' => 'removed', 'value' => $value];
            }
        }
        
        return $diff;
    }
    
    /**
     * 获取变更历史
     * 
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }
    
    /**
     * 清除历史记录
     * 
     * @return void
     */
    public function clear(): void
    {
        $this->history = [];
    }
    
    /**
     * 获取最新的变更
     * 
     * @param int $count
     * @return array
     */
    public function getLatestChanges(int $count = 5): array
    {
        return array_slice($this->history, -$count);
    }
}