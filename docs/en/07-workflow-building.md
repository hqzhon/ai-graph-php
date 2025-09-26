# LangGraph PHP SDK FAQ - Workflow Building

## How to build a multi-node workflow?
```php
$graph->addNode('start', function ($state) {
    return ['step' => 'start', 'message' => 'Workflow started'];
});

$graph->addNode('process', function ($state) {
    return ['step' => 'process', 'message' => 'Processing'];
});

$graph->addNode('end', function ($state) {
    return ['step' => 'end', 'message' => 'Workflow completed'];
});

// Add edges
$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');
```

## How to use agents in a workflow?
```php
$graph->addNode('researcher', function ($state) use ($researcherAgent) {
    $task = $state['task'] ?? 'Perform research';
    $result = $researcherAgent->execute($task, new State($state));
    return array_merge($state, $result->getData(), ['_currentNode' => 'researcher']);
});
```

## How to implement loops or iterations?
Loops can be implemented through conditional edges:

```php
$graph->addConditionalEdges('process', function ($state) {
    // Decide whether to continue looping based on certain conditions
    $counter = $state['counter'] ?? 0;
    if ($counter < 3) {
        return 'process'; // Continue looping
    }
    return 'end'; // End the loop
}, [
    'process' => 'process',
    'end' => 'end'
]);
```