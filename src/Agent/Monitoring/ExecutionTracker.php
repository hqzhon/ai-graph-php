<?php

namespace App\Agent\Monitoring;

class ExecutionTracker
{
    private $executionLog = [];
    private $startTime;
    
    public function __construct()
    {
        $this->startTime = microtime(true);
    }
    
    /**
     * 记录执行步骤
     * 
     * @param string $agentName 智能体名称
     * @param string $action 动作
     * @param array $details 详情
     * @return void
     */
    public function logStep(string $agentName, string $action, array $details = []): void
    {
        $this->executionLog[] = [
            'agent' => $agentName,
            'action' => $action,
            'details' => $details,
            'timestamp' => microtime(true),
            'elapsed' => microtime(true) - $this->startTime
        ];
    }
    
    /**
     * 获取执行日志
     * 
     * @return array
     */
    public function getLog(): array
    {
        return $this->executionLog;
    }
    
    /**
     * 获取执行统计
     * 
     * @return array
     */
    public function getStats(): array
    {
        $agentStats = [];
        $totalTime = 0;
        
        foreach ($this->executionLog as $log) {
            $agent = $log['agent'];
            if (!isset($agentStats[$agent])) {
                $agentStats[$agent] = [
                    'count' => 0,
                    'totalTime' => 0
                ];
            }
            
            $agentStats[$agent]['count']++;
            $agentStats[$agent]['totalTime'] += $log['elapsed'];
            $totalTime = max($totalTime, $log['elapsed']);
        }
        
        return [
            'agentStats' => $agentStats,
            'totalTime' => $totalTime,
            'stepCount' => count($this->executionLog)
        ];
    }
}