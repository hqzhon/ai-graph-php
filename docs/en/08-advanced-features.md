# LangGraph PHP SDK FAQ - Advanced Features

## What is checkpointing?
Checkpointing allows you to save the state of a workflow and restore it later. This is particularly useful for long-running workflows.

## How to use checkpoints?
```php
use LangGraph\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

// Set a checkpoint saver on the graph
$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

$runnable = $graph->compile();

// Run the workflow with a unique ID to enable checkpointing
$threadId = 'user_session_123';
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState, $threadId);

// You can now retrieve checkpoints for this thread
$checkpoints = $checkpointSaver->list($threadId);
```

## What is interruption and resumption?
You can interrupt the execution of a workflow and resume it later:

```php
try {
    $finalState = $compiled->execute($initialState, $threadId, ['process'], []);
} catch (InterruptedException $e) {
    echo "Interrupted before node: " . $e->getNodeKey() . "\n";
}
```

## How to implement streaming responses?
```php
foreach ($compiled->stream($initialState, $threadId) as $state) {
    echo "Step: " . ($state->get('step') ?? 'unknown') . "\n";
}
```