<?php

namespace App\Agent\Communication;

class MessageQueue
{
    private $messages = [];
    
    /**
     * 发送消息
     * 
     * @param Message $message 消息
     * @return void
     */
    public function send(Message $message): void
    {
        $this->messages[] = $message;
    }
    
    /**
     * 接收消息
     * 
     * @param string $recipient 接收者
     * @return Message|null 消息或null（如果没有消息）
     */
    public function receive(string $recipient): ?Message
    {
        foreach ($this->messages as $index => $message) {
            if ($message->getRecipient() === $recipient) {
                // 移除消息（模拟消费）
                unset($this->messages[$index]);
                // 重新索引数组
                $this->messages = array_values($this->messages);
                return $message;
            }
        }
        
        return null;
    }
    
    /**
     * 获取所有消息
     * 
     * @return array
     */
    public function getAllMessages(): array
    {
        return $this->messages;
    }
    
    /**
     * 清空消息队列
     * 
     * @return void
     */
    public function clear(): void
    {
        $this->messages = [];
    }
}