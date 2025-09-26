# LangGraph PHP SDK FAQ - 高级特性

## 什么是检查点（Checkpointing）？
检查点允许您保存工作流的状态并在以后恢复。这对于长时间运行的工作流特别有用。

## 如何使用检查点？
```php
use LangGraph\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

// 在图上设置检查点保存器
$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

$runnable = $graph->compile();

// 使用唯一ID运行工作流以启用检查点
$threadId = 'user_session_123';
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState, $threadId);

// 现在您可以获取此线程的检查点
$checkpoints = $checkpointSaver->list($threadId);
```

## 什么是中断和恢复？
您可以中断工作流的执行并在以后恢复：

```php
try {
    $finalState = $compiled->execute($initialState, $threadId, ['process'], []);
} catch (InterruptedException $e) {
    echo "在节点前中断: " . $e->getNodeKey() . "\n";
}
```

## 如何实现流式响应？
```php
foreach ($compiled->stream($initialState, $threadId) as $state) {
    echo "步骤: " . ($state->get('step') ?? '未知') . "\n";
}
```