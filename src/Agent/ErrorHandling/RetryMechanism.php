<?php

namespace App\Agent\ErrorHandling;

class RetryMechanism
{
    private $maxRetries;
    private $delay;
    
    public function __construct(int $maxRetries = 3, int $delay = 1000)
    {
        $this->maxRetries = $maxRetries;
        $this->delay = $delay; // 毫秒
    }
    
    /**
     * 执行带重试的函数
     * 
     * @param callable $function 要执行的函数
     * @param array $args 参数
     * @return mixed
     * @throws \Exception
     */
    public function execute(callable $function, array $args = [])
    {
        $lastException = null;
        
        for ($i = 0; $i <= $this->maxRetries; $i++) {
            try {
                return call_user_func_array($function, $args);
            } catch (\Exception $e) {
                $lastException = $e;
                
                // 如果不是最后一次重试，等待后重试
                if ($i < $this->maxRetries) {
                    usleep($this->delay * 1000); // 转换为微秒
                }
            }
        }
        
        // 如果所有重试都失败，抛出最后一个异常
        throw $lastException;
    }
}