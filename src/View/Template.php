<?php

namespace App\View;

class Template
{
    private $templateDir;
    
    public function __construct($templateDir)
    {
        $this->templateDir = $templateDir;
    }
    
    public function render($template, $variables = [])
    {
        $templateFile = $this->templateDir . '/' . $template . '.php';
        
        if (!file_exists($templateFile)) {
            throw new \Exception("Template file not found: $templateFile");
        }
        
        // 提取变量到局部作用域
        extract($variables);
        
        // 开启输出缓冲
        ob_start();
        include $templateFile;
        $content = ob_get_clean();
        
        return $content;
    }
}