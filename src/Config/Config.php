<?php

namespace App\Config;

class Config
{
    private static $config = [];
    
    public static function load($configFile)
    {
        if (file_exists($configFile)) {
            self::$config = array_merge(self::$config, include $configFile);
        }
    }
    
    public static function get($key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }
}