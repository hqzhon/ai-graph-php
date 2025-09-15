<?php

namespace App\Agent\Memory;

class ContextManager
{
    private $shortTermMemory;
    private $conversationHistory = [];
    
    public function __construct()
    {
        $this->shortTermMemory = new ShortTermMemory();
    }
    
    /**
     * 添加对话历史
     * 
     * @param string $role 角色（user/assistant/system）
     * @param string $content 内容
     * @return void
     */
    public function addHistory(string $role, string $content): void
    {
        $this->conversationHistory[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => microtime(true)
        ];
    }
    
    /**
     * 获取对话历史
     * 
     * @param int $limit 限制条数
     * @return array
     */
    public function getHistory(int $limit = 10): array
    {
        $count = count($this->conversationHistory);
        $start = max(0, $count - $limit);
        return array_slice($this->conversationHistory, $start);
    }
    
    /**
     * 清空对话历史
     * 
     * @return void
     */
    public function clearHistory(): void
    {
        $this->conversationHistory = [];
    }
    
    /**
     * 获取短期记忆
     * 
     * @return ShortTermMemory
     */
    public function getShortTermMemory(): ShortTermMemory
    {
        return $this->shortTermMemory;
    }
}