<?php

namespace App\Agent\Collaboration;

use App\Agent\AgentInterface;
use App\LangGraph\State\GraphState;

class BaseDecisionMechanism implements DecisionMechanismInterface
{
    public function makeDecision(array $agents, string $decisionPoint, GraphState $state)
    {
        // 收集智能体意见
        $opinions = $this->collectOpinions($agents, $decisionPoint, $state);
        
        // 整合意见
        $decision = $this->integrateOpinions($opinions, $state);
        
        // 记录决策
        $state->set('decision_' . $decisionPoint, $decision);
        $state->set('decision_made_at', microtime(true));
        
        return $decision;
    }
    
    public function collectOpinions(array $agents, string $topic, GraphState $state): array
    {
        $opinions = [];
        
        foreach ($agents as $agent) {
            // 模拟智能体提供意见
            $opinion = $this->getAgentOpinion($agent, $topic, $state);
            if ($opinion !== null) {
                $opinions[$agent->getName()] = $opinion;
            }
        }
        
        return $opinions;
    }
    
    public function integrateOpinions(array $opinions, GraphState $state)
    {
        // 简单的多数投票机制
        if (empty($opinions)) {
            return null;
        }
        
        // 统计每个意见的票数
        $voteCount = [];
        foreach ($opinions as $opinion) {
            $key = is_array($opinion) ? json_encode($opinion) : (string)$opinion;
            $voteCount[$key] = ($voteCount[$key] ?? 0) + 1;
        }
        
        // 选择得票最多的意见
        arsort($voteCount);
        $winner = key($voteCount);
        
        // 如果原始意见是数组，解码回数组
        if (strpos($winner, '{') === 0) {
            $decoded = json_decode($winner, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $winner;
        }
        
        return $winner;
    }
    
    /**
     * 获取智能体意见
     * 
     * @param AgentInterface $agent 智能体
     * @param string $topic 议题
     * @param GraphState $state 状态
     * @return mixed 意见
     */
    protected function getAgentOpinion(AgentInterface $agent, string $topic, GraphState $state)
    {
        // 简化实现：基于智能体名称和议题生成意见
        return "Opinion from " . $agent->getName() . " on " . $topic;
    }
}