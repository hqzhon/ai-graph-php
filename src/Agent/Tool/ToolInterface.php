<?php

namespace App\Agent\Tool;

interface ToolInterface
{
    /**
     * 获取工具名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 获取工具描述
     * 
     * @return string
     */
    public function getDescription(): string;
    
    /**
     * 执行工具
     * 
     * @param array $parameters 参数
     * @return mixed 结果
     */
    public function execute(array $parameters);
}