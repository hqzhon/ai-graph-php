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
        $viewFile = $this->templateDir . '/' . $template . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("Template file not found: {$viewFile}");
        }

        // Render the specific view file to a variable
        ob_start();
        extract($variables);
        include $viewFile;
        $content = ob_get_clean();

        // Now render the main layout, passing the view content and other variables
        $layoutFile = $this->templateDir . '/layout.php';
        ob_start();
        extract($variables);
        include $layoutFile;
        return ob_get_clean();
    }
}