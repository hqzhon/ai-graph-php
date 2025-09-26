# LangGraph PHP SDK FAQ - AI Model Integration

## Which AI models are supported?
Currently supported AI models:
- DeepSeek series models
- Qwen (Tongyi Qianwen) series models

## How to use AI models?
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

## How to handle model API errors?
Model clients throw RuntimeException exceptions, which you can handle using try-catch blocks:

```php
try {
    $response = $client->chatComplete($messages);
    echo "Response: " . $response . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```