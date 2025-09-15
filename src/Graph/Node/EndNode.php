<?php

namespace App\Graph\Node;

class EndNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 结束工作流
        $state['step'] = 'end';
        $state['message'] = 'Workflow completed';
        $state['completed_at'] = date('Y-m-d H:i:s');
        
        return $state;
    }
}