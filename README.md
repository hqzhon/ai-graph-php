# PHP MVP with Workflow Engine

A minimal viable product (MVP) implementation of a PHP application with Workflow engine integration, built as a demonstration of how to structure a PHP project with modern practices.

## Features

- Basic MVC structure with routing
- Template engine for rendering views
- Integrated Symfony Workflow component for state management
- Custom LangGraph-like workflow engine implementation
- Model client factory supporting DeepSeek and Qwen
- Multi-agent system with communication and coordination
- Advanced collaborative AI system with swarm intelligence
- Article management with state transitions (draft, review, published, rejected)
- Service container for dependency management
- Console commands support
- PHPUnit testing setup

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/hqzhon/ai-graph-php
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

Test LangGraph implementation:
```
php bin/test-langgraph.php
```

Debug LangGraph implementation:
```
php bin/debug-langgraph.php
```

Test multi-agent system:
```
php bin/test-multiagent.php
```

Test advanced collaborative AI system:
```
php bin/test-advanced-collaboration.php
```

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
│   ├── Graph/           # Custom workflow engine
│   │   ├── Node/        # Workflow nodes
│   │   ├── Edge/        # Workflow edges
│   │   ├── State/       # State management
│   │   ├── Executor/    # Workflow executor
│   │   └── Example/     # Example workflows
│   └── LangGraph/       # LangGraph-like implementation
│       ├── Node/        # LangGraph nodes
│       ├── Edge/        # LangGraph edges
│       ├── State/       # LangGraph state management
│       ├── Example/     # Example LangGraph workflows
│       ├── GraphInterface.php  # Graph interface
│       ├── BaseGraph.php       # Base graph implementation
│       ├── StateGraph.php      # State graph implementation
│       └── CompiledGraph.php   # Compiled graph execution
├── templates/           # Template files
├── tests/               # Test files
├── var/                 # Variable files (logs, cache, etc.)
└── vendor/              # Composer dependencies
```

## Workflow Engines

This project includes three workflow engines:

### 1. Symfony Workflow Component

Used for managing article states:
- **States**: draft, review, published, rejected
- **Transitions**: 
  - request_review (draft → review)
  - publish (review → published)
  - reject (review → rejected)
  - rework (rejected → draft)

### 2. Custom Workflow Engine

A custom implementation of workflow concepts:
- **Nodes**: Represent steps or tasks in a workflow
- **Edges**: Connect nodes and define execution paths
- **State**: Maintains the current state of the workflow
- **Executor**: Runs the workflow by executing nodes and following edges

### 3. LangGraph-like Implementation

A PHP implementation of core LangGraph concepts:
- **Graph**: Represents the workflow structure
- **Nodes**: Callable functions that perform actions
- **Edges**: Connections between nodes
- **Conditional Edges**: Edges that are selected based on conditions
- **State**: Maintains the current state of the workflow
- **Compilation**: Converts the graph into an executable form

#### Usage Example

```php
use App\LangGraph\StateGraph;
use App\LangGraph\State\GraphState;

// Create a state graph
$graph = new StateGraph(GraphState::class);

// Add nodes
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
$initialState = new GraphState(['workflow' => 'example']);
$finalState = $compiled->execute($initialState);

print_r($finalState->getData());
```

## Multi-Agent System

This project includes a multi-agent system with the following features:

### Core Components

1. **Agents**: Autonomous entities that can perform actions
2. **Communication**: Message passing between agents
3. **Memory**: Short-term and context memory management
4. **Tools**: Plugin system for extending agent capabilities
5. **Monitoring**: Execution tracking and debugging
6. **Error Handling**: Retry mechanisms and state recovery

### Agent Types

- **ResponseAgent**: Simple agent that returns predefined responses
- **ModelBasedAgent**: Agent that uses AI models (DeepSeek, Qwen) for responses

### Usage Example

```php
use App\Agent\AgentFactory;
use App\Agent\Tool\ToolManager;
use App\Agent\Tool\CalculatorTool;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;

// Create factories
$modelConfig = new ModelConfig();
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// Create agents
$plannerAgent = $agentFactory->createModelBasedAgent(
    'planner',
    'deepseek',
    'You are a planning assistant.'
);

$executorAgent = $agentFactory->createModelBasedAgent(
    'executor',
    'qwen',
    'You are an execution assistant.'
);

// Create tool manager and register tools
$toolManager = new ToolManager();
$toolManager->register(new CalculatorTool());

// Use agents in a workflow
```

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

### Usage Example

```php
use App\Agent\Example\AdvancedCollaborativeWorkflow;

// Run an advanced collaborative workflow
$finalState = AdvancedCollaborativeWorkflow::run(
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
