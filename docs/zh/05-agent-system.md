# LangGraph PHP SDK FAQ - 智能体系统

## 什么是智能体？
智能体是封装了特定功能的组件，可以是基于模型的智能体（连接到AI模型）或响应智能体（返回预定义响应）。

## 如何创建响应智能体？
```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

$modelConfig = new ModelConfig(require 'config/model.php');
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

$responseAgent = $agentFactory->createResponseAgent(
    'test_agent',
    '这是预定义的响应',
    '测试智能体'
);
```

## 如何创建基于模型的智能体？
```php
$modelBasedAgent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek',
    '你是一个研究助理。'
);
```

## 智能体系统包含哪些组件？
智能体系统包含以下组件：
- **工具系统**: 为智能体提供执行特定任务的能力
- **记忆系统**: 管理智能体的短期记忆和上下文
- **通信系统**: 处理智能体间的消息传递
- **错误处理**: 提供重试机制和错误恢复