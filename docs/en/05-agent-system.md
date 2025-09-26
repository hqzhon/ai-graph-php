# LangGraph PHP SDK FAQ - Agent System

## What is an agent?
An agent is a component that encapsulates specific functionality. It can be either a model-based agent (connected to an AI model) or a response agent (returning a predefined response).

## How to create a response agent?
```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

$modelConfig = new ModelConfig(require 'config/model.php');
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

$responseAgent = $agentFactory->createResponseAgent(
    'test_agent',
    'This is a predefined response',
    'Test agent'
);
```

## How to create a model-based agent?
```php
$modelBasedAgent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek',
    'You are a research assistant.'
);
```

## What components does the agent system include?
The agent system includes the following components:
- **Tool System**: Provides agents with the ability to perform specific tasks
- **Memory System**: Manages agents' short-term memory and context
- **Communication System**: Handles message passing between agents
- **Error Handling**: Provides retry mechanisms and error recovery