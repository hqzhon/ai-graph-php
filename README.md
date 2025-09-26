# LangGraph PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/langgraph/langgraph-php.svg?style=flat-square)](https://packagist.org/packages/langgraph/langgraph-php)

A powerful PHP implementation of Python's LangGraph. This SDK enables you to build stateful, multi-agent applications with complex, graph-based logic. It provides the core components to create robust and scalable AI workflows in your PHP projects.

## ✨ Core Features

*   **Stateful Graphs**: Create graphs where state is passed between nodes, allowing for complex, long-running workflows.
*   **Node & Edge Control**: Define nodes as units of work and use edges to control the flow, including conditional branching.
*   **Agent System**: A built-in factory for creating and managing AI agents.
*   **AI Model Integration**: Easily connect to various large language models (e.g., DeepSeek, Qwen).
*   **Persistence (Checkpointing)**: Save and restore the state of your graphs, allowing workflows to be paused and resumed.
*   **Streaming**: Support for real-time streaming of responses from nodes.

## 🚀 Installation

You can install the package via Composer:

```bash
composer require langgraph/langgraph-php
```

## 🛠️ Usage & Functionality

Here’s how to use the core features of the LangGraph PHP SDK.

### 1. Creating a Basic Workflow

The fundamental building block is the `StateGraph`. You define a state class, add nodes (functions or callables), and connect them with edges.

```php
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\State;

// 1. Define the state structure for your graph
class MyState extends State {
    // Your state properties here
}

// 2. Initialize the graph with the state class
$graph = new StateGraph(MyState::class);

// 3. Add nodes, which are functions that modify the state
$graph->addNode('start', function (MyState $state) {
    echo "Executing node: start\n";
    $state->set('message', 'Hello from the start!');
    return $state;
});

$graph->addNode('middle', function (MyState $state) {
    echo "Executing node: middle\n";
    $state->set('message', $state->get('message') . ' ... and now the middle!');
    return $state;
});

$graph->addNode('end', function (MyState $state) {
    echo "Executing node: end\n";
    $state->set('message', $state->get('message') . ' ... finished!');
    return $state;
});

// 4. Define the workflow by adding edges
$graph->addEdge('start', 'middle');
$graph->addEdge('middle', 'end');

// 5. Set the entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 6. Compile the graph and run it
$runnable = $graph->compile();
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState);

print_r($finalState->getData());
// Outputs: [ 'value' => 1, 'message' => 'Hello from the start! ... and now the middle! ... finished!' ]
```

### 2. Conditional Logic

You can direct the flow of your graph based on the current state using conditional edges. This allows for dynamic, branching workflows.

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

### 3. Using AI Agents

The SDK includes an `AgentFactory` to easily create model-based agents that can be used as nodes in your graph.

First, configure your model API keys in `config/model.php`.

```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

// Setup factories
$modelConfig = new ModelConfig(require 'config/model.php');
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// Create an agent
$researcherAgent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek', // Or another model like 'qwen'
    'You are a helpful research assistant.'
);

// Use the agent to process a query
$response = $researcherAgent->execute('What is LangGraph?');
echo $response;
```

### 4. Persistence with Checkpoints

Save the state of a workflow at any point and resume it later. The SDK includes a `MemoryCheckpointSaver` for simple use cases.

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

## 📚 Documentation

For detailed documentation and FAQs, please refer to our documentation:

- [中文文档](docs/zh/README.md) - Chinese documentation
- [English Documentation](docs/en/README.md) - English documentation

## Advanced Examples

For more complex examples, including multi-agent collaboration, streaming, and web integrations, please explore the scripts in the `bin/` and `examples/` directories.

## 🤝 Contributing

Contributions are welcome! Please read `CONTRIBUTING.md` for details.

## 📄 License

This project is licensed under the MIT License. See the `LICENSE` file for more information.