<?php

namespace App\Agent\Collaboration;

use App\UnifiedGraph\State\State;

interface CollaborationProtocolInterface
{
    /**
     * 获取协作协议名称
     * 
     * @return string
     */
    public function getName(): string;
    
    /**
     * 初始化协作
     * 
     * @param string $task 协作任务
     * @return State 初始状态
     */
    public function initialize(string $task): State;
    
    /**
     * 执行协作步骤
     * 
     * @param State $state 当前状态
     * @return State 更新后的状态
     */
    public function executeStep(State $state): State;
    
    /**
     * 检查协作是否完成
     * 
     * @param State $state 当前状态
     * @return bool 是否完成
     */
    public function isComplete(State $state): bool;
}