<?php

namespace App\Service;

use App\Service\ArticleWorkflow;

class ServiceContainer
{
    private static $services = [];
    
    public static function get($name)
    {
        if (!isset(self::$services[$name])) {
            self::$services[$name] = self::createService($name);
        }
        
        return self::$services[$name];
    }
    
    private static function createService($name)
    {
        switch ($name) {
            case 'article_workflow':
                return new ArticleWorkflow();
            default:
                throw new \Exception("Service not found: $name");
        }
    }
}