<?php

namespace App\Graph\Node;

class ProcessNode extends AbstractNode
{
    protected function process(array $state): array
    {
        // 处理逻辑
        $state['step'] = 'processing';
        $state['processed_data'] = 'Data processed at ' . date('Y-m-d H:i:s');
        
        // 模拟一些处理时间
        sleep(1);
        
        return $state;
    }
}