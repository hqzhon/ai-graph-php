# LangGraph PHP SDK FAQ - Core Features

## What is StateGraph?
StateGraph is the fundamental building block of the LangGraph PHP SDK. It allows you to define a state class, add nodes (functions or callables), and connect them with edges to form a workflow.

## How to create a basic workflow?
```php
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\State;

// 1. Define the state structure for your graph
class MyState extends State {
    // Your state properties here
}

// 2. Initialize the graph with the state class
$graph = new StateGraph(MyState::class);

// 3. Add nodes
$graph->addNode('start', function (MyState $state) {
    echo "Executing node: start\n";
    $state->set('message', 'Hello from the start!');
    return $state;
});

$graph->addNode('end', function (MyState $state) {
    echo "Executing node: end\n";
    $state->set('message', $state->get('message') . ' ... finished!');
    return $state;
});

// 4. Add edges
$graph->addEdge('start', 'end');

// 5. Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 6. Compile and run the graph
$runnable = $graph->compile();
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState);
```

## How to implement conditional logic?
You can direct the flow of your graph based on the current state using conditional edges:

```php
// A function to decide the next step
$decider = function (MyState $state) {
    if ($state->get('value', 0) > 5) {
        return 'end_workflow';
    } else {
        return 'continue_processing';
    }
};

// Add a conditional edge from 'start'
$graph->addConditionalEdges('start', $decider, [
    'end_workflow' => 'end',
    'continue_processing' => 'middle',
]);
```