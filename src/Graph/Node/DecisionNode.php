<?php

namespace App\Graph\Node;

class DecisionNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 决策节点，根据某些条件决定下一步
        $state['step'] = 'decision';
        
        // 模拟决策逻辑
        $random = rand(0, 1);
        if ($random === 0) {
            $state['decision'] = 'path_a';
            $state['message'] = 'Taking path A';
        } else {
            $state['decision'] = 'path_b';
            $state['message'] = 'Taking path B';
        }
        
        return $state;
    }
}