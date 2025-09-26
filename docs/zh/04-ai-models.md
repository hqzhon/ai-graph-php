# LangGraph PHP SDK FAQ - AI模型集成

## 支持哪些AI模型？
目前支持以下AI模型：
- DeepSeek系列模型
- Qwen（通义千问）系列模型

## 如何使用AI模型？
```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

// 设置工厂
$modelConfig = new ModelConfig(require 'config/model.php');
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// 创建智能体
$researcherAgent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek', // 或其他模型如'qwen'
    '你是一个乐于助人的研究助理。'
);

// 使用智能体处理查询
$response = $researcherAgent->execute('什么是LangGraph？');
echo $response;
```

## 如何处理模型API错误？
模型客户端会抛出RuntimeException异常，您可以使用try-catch块来处理：

```php
try {
    $response = $client->chatComplete($messages);
    echo "响应: " . $response . "\n";
} catch (Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
}
```