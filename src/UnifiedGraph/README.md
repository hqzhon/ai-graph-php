# Unified Graph Implementation

This directory contains a unified implementation that combines the best features of both the `LangGraph` and `Graph` implementations in this project.

## Features

1. **Flexible Node Types**: Supports both callable functions (LangGraph style) and Node objects (Graph style)
2. **Unified State Management**: Single State interface and implementation that works with both approaches
3. **Conditional Edges**: Supports complex conditional routing like LangGraph
4. **Streaming Execution**: Can stream intermediate states during execution
5. **Mixed Workflows**: Allows combining different node types in a single workflow

## Components

- `State/`: Unified state management with StateInterface and State implementation
- `Node/`: Node interface and base implementations including CallableNode for wrapping functions
- `Edge/`: Edge interface and implementation with condition support
- `Executor/`: Executor class for running workflows in the Graph style
- `BaseGraph.php`, `StateGraph.php`, `CompiledGraph.php`: Core graph classes similar to LangGraph
- `Example/`: Example workflows demonstrating different approaches

## Usage

### LangGraph-style Workflow

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;

$graph = new StateGraph(State::class);

// Add callable nodes
$graph->addNode('start', function ($state) {
    return ['step' => 'start', 'message' => 'Workflow started'];
});

// Add edges
$graph->addEdge('start', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// Compile and execute
$compiled = $graph->compile();
$initialState = new State(['workflow' => 'example']);
$finalState = $compiled->execute($initialState);
```

### Graph-style Workflow

```php
use App\UnifiedGraph\Executor\Executor;
use App\UnifiedGraph\Node\AbstractNode;
use App\UnifiedGraph\Edge\Edge;
use App\UnifiedGraph\State\State;

class StartNode extends AbstractNode {
    protected function process(array $state): array {
        $state['step'] = 'start';
        $state['message'] = 'Workflow started';
        return $state;
    }
}

$executor = new Executor();
$startNode = new StartNode('start');
$executor->addNode($startNode);
$executor->addEdge(new Edge('start', 'end'));
$executor->setStartNode('start');

$initialState = new State(['workflow' => 'example']);
$finalState = $executor->execute($initialState);
```

### Mixed Workflow

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\Node\AbstractNode;
use App\UnifiedGraph\State\State;

class ProcessNode extends AbstractNode {
    protected function process(array $state): array {
        $state['step'] = 'process';
        $state['message'] = 'Processed by node class';
        return $state;
    }
}

$graph = new StateGraph(State::class);

// Mix callable and node object
$graph->addNode('start', function ($state) {
    return ['step' => 'start', 'message' => 'Started with callable'];
});

$graph->addNode('process', new ProcessNode('process'));

$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

$graph->setEntryPoint('start');
$graph->setFinishPoint('end');
```

## Benefits of the Unified Approach

1. **Flexibility**: Developers can choose the approach that best fits their needs
2. **Compatibility**: Existing code using either approach can be easily migrated
3. **Extensibility**: New features can be added to the unified implementation
4. **Reduced Duplication**: Eliminates overlapping functionality between the two separate implementations