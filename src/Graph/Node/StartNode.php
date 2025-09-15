<?php

namespace App\Graph\Node;

class StartNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 初始化工作流状态
        $state['step'] = 'start';
        $state['message'] = 'Workflow started';
        $state['timestamp'] = date('Y-m-d H:i:s');
        
        return $state;
    }
}