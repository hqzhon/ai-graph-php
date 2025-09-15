<?php

namespace App\Model\Client;

interface ModelClientInterface
{
    /**
     * 发送聊天完成请求
     * 
     * @param array $messages 消息数组
     * @param array $options 附加选项
     * @return string 模型响应
     */
    public function chatComplete(array $messages, array $options = []): string;
    
    /**
     * 获取模型名称
     * 
     * @return string
     */
    public function getModelName(): string;
}