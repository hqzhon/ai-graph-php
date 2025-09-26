# LangGraph PHP SDK FAQ - 工作流构建

## 如何构建多节点工作流？
```php
$graph->addNode('start', function ($state) {
    return ['step' => 'start', 'message' => '工作流开始'];
});

$graph->addNode('process', function ($state) {
    return ['step' => 'process', 'message' => '处理中'];
});

$graph->addNode('end', function ($state) {
    return ['step' => 'end', 'message' => '工作流完成'];
});

// 添加边
$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

// 设置入口和终点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');
```

## 如何在工作流中使用智能体？
```php
$graph->addNode('researcher', function ($state) use ($researcherAgent) {
    $task = $state['task'] ?? '执行研究';
    $result = $researcherAgent->execute($task, new State($state));
    return array_merge($state, $result->getData(), ['_currentNode' => 'researcher']);
});
```

## 如何实现循环或迭代？
可以通过条件边实现循环：

```php
$graph->addConditionalEdges('process', function ($state) {
    // 根据某些条件决定是否继续循环
    $counter = $state['counter'] ?? 0;
    if ($counter < 3) {
        return 'process'; // 继续循环
    }
    return 'end'; // 结束循环
}, [
    'process' => 'process',
    'end' => 'end'
]);
```