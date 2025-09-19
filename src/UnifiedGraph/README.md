# Unified Graph Implementation

This directory contains a unified implementation that combines the best features of both the `LangGraph` and `Graph` implementations in this project.

## Features

1. **Flexible Node Types**: Supports both callable functions (LangGraph style) and Node objects (Graph style)
2. **Unified State Management**: Single State interface and implementation that works with both approaches
3. **Conditional Edges**: Supports complex conditional routing like LangGraph
4. **Streaming Execution**: Can stream intermediate states during execution
5. **Mixed Workflows**: Allows combining different node types in a single workflow
6. **Channel-based State Management**: Implements channel mechanisms similar to Python LangGraph for advanced state management
7. **Checkpoint and Persistence**: Supports saving and restoring workflow state
8. **Interruption and Resumption**: Can interrupt execution at specific nodes and resume later
9. **Enhanced Error Handling**: Comprehensive exception hierarchy for better error management
10. **State Tracking**: Built-in state change tracking for debugging and monitoring
11. **Execution Timeout Control**: Prevents infinite loops with configurable timeouts
12. **Performance Optimizations**: Reduced state copying overhead with optional tracking

## Components

- `State/`: Unified state management with StateInterface and State implementation
  - `ChannelInterface.php`: Channel interface for state management
  - `LastValueChannel.php`: Channel that keeps the last value
  - `BinaryOperatorAggregateChannel.php`: Channel that aggregates values with a binary operator
  - `ChannelsState.php`: State implementation with channel support
  - `StateTracker.php`: Tracks state changes for debugging
- `Node/`: Node interface and base implementations including CallableNode for wrapping functions
- `Edge/`: Edge interface and implementation with condition support
- `Executor/`: Executor class for running workflows in the Graph style
- `BaseGraph.php`, `StateGraph.php`, `CompiledGraph.php`: Core graph classes similar to LangGraph
- `Checkpoint/`: Checkpoint functionality for persistence
- `Exception/`: Exception hierarchy for error handling
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

### Advanced Workflow with Channels

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\State\State;

// Create a state graph with channel support
$graph = new StateGraph(ChannelsState::class);

// Add channel definitions
$graph->addChannels([
    'messages' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            if (is_array($a) && is_array($b)) {
                return array_merge($a, $b);
            }
            return $b;
        },
        'default' => []
    ],
    'step' => [
        'type' => 'last_value',
        'default' => 'start'
    ]
]);

// Add nodes
$graph->addNode('start', function ($state) {
    return [
        'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
        'step' => 'start'
    ];
});

$graph->addNode('process', function ($state) {
    $messages = $state['messages'] ?? [];
    $messages[] = ['role' => 'user', 'content' => 'Hello, what is the weather today?'];
    return [
        'messages' => $messages,
        'step' => 'process'
    ];
});

$graph->addNode('end', function ($state) {
    $messages = $state['messages'] ?? [];
    $messages[] = ['role' => 'assistant', 'content' => 'The weather is sunny today.'];
    return [
        'messages' => $messages,
        'step' => 'end'
    ];
});

// Add edges
$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// Compile and execute
$compiled = $graph->compile();
$initialState = new State(['workflow' => 'advanced_example']);
$finalState = $compiled->execute($initialState);

// Access state data
$data = $finalState->getData();
```

### Workflow with Interruption

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\State\State;
use App\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

// Create a state graph
$graph = new StateGraph(ChannelsState::class);

// Add channel definitions
$graph->addChannels([
    'step' => [
        'type' => 'last_value',
        'default' => 'start'
    ]
]);

// Add nodes
$graph->addNode('start', function ($state) {
    return ['step' => 'start'];
});

$graph->addNode('process', function ($state) {
    return ['step' => 'process'];
});

$graph->addNode('end', function ($state) {
    return ['step' => 'end'];
});

// Add edges
$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// Set checkpoint saver
$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

// Compile
$compiled = $graph->compile();

// Create initial state
$initialState = new State(['workflow' => 'interrupt_example']);

// Execute with interruption before 'process' node
$threadId = 'example_thread_1';
try {
    $finalState = $compiled->execute($initialState, $threadId, ['process'], []);
} catch (InterruptedException $e) {
    echo "Workflow interrupted before node: " . $e->getNodeKey() . "\n";
}
```

### Advanced Workflow with Qwen API Integration

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\ChannelsState;
use App\UnifiedGraph\State\State;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

// Create configuration and factory
$config = new ModelConfig();
$factory = new ModelFactory($config->all());

// Create a state graph with channel support
$graph = new StateGraph(ChannelsState::class);

// Add channel definitions
$graph->addChannels([
    'messages' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            if (is_array($a) && is_array($b)) {
                return array_merge($a, $b);
            }
            return $b;
        },
        'default' => []
    ],
    'step' => [
        'type' => 'last_value',
        'default' => 'start'
    ],
    'response' => [
        'type' => 'last_value',
        'default' => ''
    ]
]);

// Add nodes
$graph->addNode('start', function ($state) {
    return [
        'messages' => [['role' => 'system', 'content' => 'You are a helpful assistant.']],
        'step' => 'start'
    ];
});

// Add node with Qwen API call
$graph->addNode('qwen_query', function ($state) use ($factory) {
    $messages = $state['messages'] ?? [];
    $messages[] = ['role' => 'user', 'content' => 'What is the capital of France?'];
    
    try {
        $client = $factory->createClient('qwen');
        $response = $client->chatComplete($messages);
        
        return [
            'messages' => $messages,
            'step' => 'qwen_query',
            'response' => $response
        ];
    } catch (Exception $e) {
        return [
            'messages' => $messages,
            'step' => 'qwen_query',
            'response' => 'Error: ' . $e->getMessage()
        ];
    }
});

$graph->addNode('end', function ($state) {
    $messages = $state['messages'] ?? [];
    $response = $state['response'] ?? 'No response';
    $messages[] = ['role' => 'assistant', 'content' => $response];
    
    return [
        'messages' => $messages,
        'step' => 'end',
        'response' => $response
    ];
});

// Add edges
$graph->addEdge('start', 'qwen_query');
$graph->addEdge('qwen_query', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// Compile and execute
$compiled = $graph->compile();
$initialState = new State(['workflow' => 'qwen_integration_example']);
$finalState = $compiled->execute($initialState);

// Access state data
$data = $finalState->getData();
echo "Response: " . ($data['response'] ?? 'No response') . "\n";
```

## Benefits of the Unified Approach

1. **Flexibility**: Developers can choose the approach that best fits their needs
2. **Compatibility**: Existing code using either approach can be easily migrated
3. **Extensibility**: New features can be added to the unified implementation
4. **Reduced Duplication**: Eliminates overlapping functionality between the two separate implementations
5. **Enhanced Capabilities**: Adds advanced features like channels, checkpointing, and interruption that bring the PHP implementation closer to the Python version
6. **Production Ready**: Includes comprehensive error handling, state tracking, and performance optimizations for real-world applications