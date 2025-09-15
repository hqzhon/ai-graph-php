<?php

namespace App;

use App\Config\Config;
use App\Logger\Logger;

class Kernel
{
    private $logger;
    
    public function __construct()
    {
        // 初始化日志记录器
        $this->logger = new Logger(__DIR__ . '/../var/logs/app.log');
        
        // 记录应用启动
        $this->logger->info('Application started');
    }
    
    public function getLogger()
    {
        return $this->logger;
    }
}