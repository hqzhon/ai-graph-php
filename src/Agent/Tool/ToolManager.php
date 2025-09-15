<?php

namespace App\Agent\Tool;

class ToolManager
{
    private $tools = [];
    
    /**
     * 注册工具
     * 
     * @param ToolInterface $tool 工具
     * @return void
     */
    public function register(ToolInterface $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }
    
    /**
     * 获取工具
     * 
     * @param string $name 工具名称
     * @return ToolInterface|null
     */
    public function get(string $name): ?ToolInterface
    {
        return $this->tools[$name] ?? null;
    }
    
    /**
     * 获取所有工具
     * 
     * @return array
     */
    public function getAll(): array
    {
        return $this->tools;
    }
    
    /**
     * 执行工具
     * 
     * @param string $name 工具名称
     * @param array $parameters 参数
     * @return mixed
     */
    public function execute(string $name, array $parameters)
    {
        $tool = $this->get($name);
        if (!$tool) {
            throw new \InvalidArgumentException("Tool not found: $name");
        }
        
        return $tool->execute($parameters);
    }
}