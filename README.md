# LangGraph PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/langgraph/langgraph-php.svg?style=flat-square)](https://packagist.org/packages/langgraph/langgraph-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/your-username/langgraph-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/your-username/langgraph-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/langgraph/langgraph-php.svg?style=flat-square)](https://packagist.org/packages/langgraph/langgraph-php)

A PHP implementation of LangGraph for building language model workflows with agents, state management, and graph execution.

## Introduction

LangGraph PHP is a PHP library that brings the power of LangGraph to the PHP ecosystem. It allows you to build complex workflows with language models, manage state, and execute graphs with conditional logic.

## Features

- **Graph-based Workflows**: Build complex workflows using a graph structure
- **State Management**: Advanced state management with channels and reducers
- **Agent System**: Create and manage AI agents with different capabilities
- **Model Integration**: Support for multiple language models (DeepSeek, Qwen, etc.)
- **Streaming Support**: Real-time streaming of model responses
- **Checkpointing**: Save and restore workflow states
- **Interruption Handling**: Pause and resume workflows at specific points
- **Tool System**: Extend agent capabilities with custom tools

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

You can install the package via composer:

```bash
composer require langgraph/langgraph-php
```

## Basic Usage

### Creating a Simple Workflow

```php
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\State;

// Create a state graph
$graph = new StateGraph(State::class);

// Add nodes
$graph->addNode('start', function ($state) {
    return ['message' => 'Hello from start node'];
});

$graph->addNode('process', function ($state) {
    return ['message' => 'Processing: ' . ($state['message'] ?? '')];
});

$graph->addNode('end', function ($state) {
    return ['message' => 'Finished: ' . ($state['message'] ?? ''), 'completed' => true];
});

// Add edges
$graph->addEdge('start', 'process');
$graph->addEdge('process', 'end');

// Set entry and finish points
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// Compile and execute
$compiled = $graph->compile();
$initialState = new State(['workflow' => 'simple_example']);
$finalState = $compiled->execute($initialState);

print_r($finalState->getData());
```

### Working with Agents

```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

// Create model configuration
$modelConfig = new ModelConfig([
    'deepseek_api_key' => 'your-api-key'
]);

// Create model factory and agent factory
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// Create a model-based agent
$agent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek',
    'You are a research assistant. Provide concise answers.'
);

// Use the agent
$context = new State(['topic' => 'climate change']);
$result = $agent->execute('What are the main causes of climate change?', $context);
```

### Conditional Edges

```php
// Add conditional edges based on state
$graph->addConditionalEdges('process', function ($state) {
    // Decision logic based on state
    $score = $state['confidence'] ?? 0;
    if ($score > 0.8) {
        return 'end';
    } elseif ($score > 0.5) {
        return 'review';
    } else {
        return 'retry';
    }
}, [
    'end' => 'end',
    'review' => 'review',
    'retry' => 'process'
]);
```

## Advanced Features

### State Channels

LangGraph PHP supports advanced state management through channels:

```php
use LangGraph\UnifiedGraph\State\ChannelsState;

$graph = new StateGraph(ChannelsState::class);

// Add channels with different behaviors
$graph->addChannels([
    'messages' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            return array_merge($a, $b);
        },
        'default' => []
    ],
    'counter' => [
        'type' => 'binary_operator',
        'operator' => function ($a, $b) {
            return $a + $b;
        },
        'default' => 0
    ]
]);
```

### Checkpointing

Save and restore workflow states:

```php
use LangGraph\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

// Execute with checkpointing
$threadId = 'workflow_123';
$finalState = $compiled->execute($initialState, $threadId);

// List checkpoints
$checkpoints = $checkpointSaver->list($threadId);
```

### Interruption Handling

Pause workflows at specific points:

```php
try {
    // Interrupt after 'process' node
    $finalState = $compiled->execute($initialState, 'thread_1', [], ['process']);
} catch (InterruptedException $e) {
    echo "Workflow interrupted at node: " . $e->getNodeKey();
}
```

## Testing

```bash
composer test
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Your Name](https://github.com/your-username)
- [All Contributors](../../contributors)

# PHP MVP with Workflow Engine

A minimal viable product (MVP) implementation of a PHP application with Workflow engine integration, built as a demonstration of how to structure a PHP project with modern practices.

## Features

- Basic MVC structure with routing
- Template engine for rendering views
- Integrated Symfony Workflow component for state management
- Unified graph implementation combining the best features of both approaches
- Model client factory supporting DeepSeek and Qwen
- Multi-agent system with communication and coordination
- Advanced collaborative AI system with swarm intelligence
- Article management with state transitions (draft, review, published, rejected)
- Service container for dependency management
- Web interface for testing AI models
- Web interface for multi-agent workflow orchestration
- Console commands support
- PHPUnit testing setup

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Install dependencies:
   ```
   composer install
   ```

## Usage

### Web Interface

Start the development server:
```
composer start
```

Then open your browser and navigate to `http://localhost:8000`.

#### Testing AI Models via Web Interface

1. Navigate to `http://localhost:8000/model-test`
2. Enter your API keys for DeepSeek and/or Qwen
3. Select a model and enter a prompt
4. Click "Test Model" to see the results

**Note:** Your API keys are not stored and are only used for the current request.

#### Multi-Agent Workflow Orchestration

1. Navigate to `http://localhost:8000/multi-agent`
2. Enter your API keys for DeepSeek and/or Qwen
3. Choose a predefined workflow or create a custom one
4. Configure agents and tasks
5. Click "Run Workflow" to execute the multi-agent workflow

### Web Demo with Laravel + React

This project includes a web demonstration using Laravel backend and React frontend:

1. Navigate to the webapp directory:
   ```
   cd examples/webapp
   ```

2. Follow the setup instructions in `examples/webapp/README.md`

### Console Commands

Run console commands:
```
php bin/console hello
```

Run workflow test:
```
php bin/test-workflow.php
```

Run graph test:
```
php bin/test-graph.php
```

Run a sample workflow:
```
php bin/console workflow:run
```

Test model APIs:
```
php bin/test-models.php
```

Or using the console command:
```
php bin/console model:test
```

Test multi-agent system:
```
php bin/test-multiagent.php
```

Test advanced collaborative AI system:
```
php bin/test-advanced-collaboration.php
```

### Web Demo with Laravel + React

This project includes a web demonstration using Laravel backend and React frontend:

1. Navigate to the webapp directory:
   ```
   cd examples/webapp
   ```

2. Follow the setup instructions in `examples/webapp/README.md`

### Running Tests

Execute PHPUnit tests:
```
composer test
```

## Project Structure

```
├── bin/                 # Executable scripts
├── config/              # Configuration files
├── public/              # Publicly accessible files
├── src/                 # Source code
│   ├── Controller/      # Controllers
│   ├── Model/           # Data models and AI model clients
│   │   ├── Client/      # Model clients (DeepSeek, Qwen)
│   │   ├── Factory/     # Model factory
│   │   └── Config/      # Model configuration
│   ├── Agent/           # Multi-agent system
│   │   ├── Communication/  # Agent communication
│   │   ├── Memory/         # Memory management
│   │   ├── Tool/           # Tools and plugins
│   │   ├── Monitoring/     # Monitoring and debugging
│   │   ├── ErrorHandling/  # Error handling and recovery
│   │   ├── Collaboration/  # Collaboration protocols and mechanisms
│   │   ├── Graph/          # Agent graph integration
│   │   ├── Example/        # Example agent workflows
│   │   ├── AgentInterface.php  # Agent interface
│   │   ├── BaseAgent.php       # Base agent implementation
│   │   ├── ResponseAgent.php   # Response agent
│   │   ├── ModelBasedAgent.php # Model-based agent
│   │   └── AgentFactory.php    # Agent factory
│   ├── Service/         # Business logic services
│   ├── View/            # View components
│   ├── Http/            # HTTP components
│   ├── Routing/         # Routing components
│   ├── Config/          # Configuration components
│   ├── Logger/          # Logging components
│   ├── Database/        # Database components
│   ├── Middleware/      # Middleware components
│   ├── Container/       # Service container
│   ├── Console/         # Console commands
│   ├── Exception/       # Custom exceptions
│   └── UnifiedGraph/    # Unified graph implementation
│       ├── Node/        # Unified nodes
│       ├── Edge/        # Unified edges
│       ├── State/       # Unified state management
│       ├── Executor/    # Unified executor
│       ├── Example/     # Example unified workflows
│       ├── GraphInterface.php  # Unified graph interface
│       ├── BaseGraph.php       # Unified base graph implementation
│       ├── StateGraph.php      # Unified state graph implementation
│       └── CompiledGraph.php   # Unified compiled graph execution
├── templates/           # Template files
├── tests/               # Test files
├── var/                 # Variable files (logs, cache, etc.)
└── vendor/              # Composer dependencies
```

## Workflow Engines

This project includes a unified workflow engine that combines the best features of multiple approaches:

### 1. Symfony Workflow Component

Used for managing article states:
- **States**: draft, review, published, rejected
- **Transitions**: 
  - request_review (draft → review)
  - publish (review → published)
  - reject (review → rejected)
  - rework (rejected → draft)

### 2. Unified Graph Implementation

A unified implementation that combines the best features of different workflow approaches:
- **Flexible Node Types**: Supports both callable functions and Node objects
- **Unified State Management**: Single State interface and implementation
- **Conditional Edges**: Supports complex conditional routing
- **Streaming Execution**: Can stream intermediate states during execution
- **Mixed Workflows**: Allows combining different node types in a single workflow

#### Usage Example

```php
use App\UnifiedGraph\StateGraph;
use App\UnifiedGraph\State\State;

// Create a state graph
$graph = new StateGraph(State::class);

// Add nodes (callable functions)
$graph->addNode('start', function ($state) {
    return ['step' => 'start', 'message' => 'Workflow started'];
});

$graph->addNode('process', function ($state) {
    return ['step' => 'process', 'message' => 'Processing data'];
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

// Compile and execute
$compiled = $graph->compile();
$initialState = new State(['workflow' => 'example']);
$finalState = $compiled->execute($initialState);

print_r($finalState->getData());
```

For more detailed examples, see `src/UnifiedGraph/README.md`.

## Advanced Collaborative AI System

This project includes an advanced collaborative AI system with the following features:

### Core Components

1. **Collaboration Protocols**: Define how agents work together
2. **Task Allocation**: Intelligent distribution of tasks among agents
3. **Dynamic Coordination**: Real-time coordination of agent activities
4. **Collaborative Decision Making**: Collective decision-making mechanisms
5. **Swarm Intelligence**:群体智能行为模拟和优化

### Key Features

- **智能体协作协议**: Advanced collaboration protocols for agent interaction
- **任务分配机制**: Smart task allocation based on agent capabilities and load
- **动态协调**: Real-time coordination and conflict resolution
- **群体决策**: Distributed and collective decision-making
- **群体智能**: Swarm intelligence simulation and optimization

### Advanced LangGraph Features

The project now includes enhanced LangGraph capabilities:

1. **Channel-based State Management**: Implements channel mechanisms similar to Python LangGraph for advanced state management
2. **Checkpoint and Persistence**: Supports saving and restoring workflow state
3. **Interruption and Resumption**: Can interrupt execution at specific nodes and resume later
4. **Enhanced Error Handling**: Comprehensive exception hierarchy for better error management
5. **State Tracking**: Built-in state change tracking for debugging and monitoring

### Usage Example

```php
use App\UnifiedGraph\Example\AdvancedWorkflowExample;

// Run an advanced collaborative workflow
$finalState = AdvancedWorkflowExample::run(
    'Research and develop a solution for reducing energy consumption in data centers'
);

print_r($finalState->getData());
```

## Model Client Factory

This project includes a model client factory that supports multiple AI models:

### Supported Models

1. **DeepSeek** - DeepSeek API client
2. **Qwen** - Aliyun Qwen API client

### Configuration

To use the model clients, you need to configure API keys. You can do this in two ways:

1. **Environment Variables**:
   ```
   export DEEPSEEK_API_KEY=your_deepseek_api_key
   export QWEN_API_KEY=your_qwen_api_key
   ```

2. **Configuration File**:
   Create a `config/model.php` file based on `config/model.example.php`:
   ```php
   <?php
   return [
       'deepseek_api_key' => 'your_deepseek_api_key_here',
       'qwen_api_key' => 'your_qwen_api_key_here',
   ];
   ```

### Usage Example

```php
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

// Create configuration
$config = new ModelConfig();

// Create factory
$factory = new ModelFactory($config->all());

// Create client
$client = $factory->createClient('deepseek'); // or 'qwen'

// Send a chat completion request
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => 'What is the capital of France?']
];

$response = $client->chatComplete($messages);
echo $response;
```

## License

MIT

## Documentation

Additional documentation can be found in the `docs/` directory, including:

- Implementation summaries
- Testing results
- Refactoring documentation
- Development process notes

These documents provide detailed information about various aspects of the project's development.