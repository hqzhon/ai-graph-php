# LangGraph PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/langgraph/langgraph-php.svg?style=flat-square)](https://packagist.org/packages/langgraph/langgraph-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/your-username/langgraph-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/your-username/langgraph-php/actions/workflows/run-tests.yml)

这是 Python LangGraph 的一个强大 PHP 实现。该 SDK 允许您使用复杂的、基于图的逻辑来构建有状态的多智能体应用。它提供了在您的 PHP 项目中创建健壮且可扩展的 AI 工作流所需的核心组件。

## ✨ 核心功能

*   **有状态图 (Stateful Graphs)**: 创建节点间传递状态的图，从而实现复杂的长期运行工作流。
*   **节点与边控制 (Node & Edge Control)**: 将节点定义为工作单元，并使用边来控制流程，包括条件分支。
*   **智能体系统 (Agent System)**: 内置工厂，用于创建和管理 AI 智能体。
*   **AI 模型集成**: 轻松连接到各种大语言模型（例如 DeepSeek, Qwen）。
*   **持久化 (Checkpointing)**: 保存和恢复图的状态，允许工作流暂停和继续。
*   **流式响应 (Streaming)**: 支持从节点实时流式传输响应。

## 🚀 安装

您可以通过 Composer 安装此包：

```bash
composer require langgraph/langgraph-php
```

## 🛠️ 功能与用法

以下是如何使用 LangGraph PHP SDK 的核心功能。

### 1. 创建一个基本工作流

`StateGraph` 是最基本的构建块。您可以定义一个状态类，添加节点（函数或可调用对象），然后用边将它们连接起来。

```php
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\State;

// 1. 为您的图定义状态结构
class MyState extends State {
    // 在此定义您的状态属性
}

// 2. 使用状态类初始化图
$graph = new StateGraph(MyState::class);

// 3. 添加节点，节点是修改状态的函数
$graph->addNode('start', function (MyState $state) {
    echo "正在执行节点: start\n";
    $state->set('message', '从起点开始！');
    return $state;
});

$graph->addNode('middle', function (MyState $state) {
    echo "正在执行节点: middle\n";
    $state->set('message', $state->get('message') . ' ... 现在到达中间点！');
    return $state;
});

$graph->addNode('end', function (MyState $state) {
    echo "正在执行节点: end\n";
    $state->set('message', $state->get('message') . ' ... 完成！');
    return $state;
});

// 4. 通过添加边来定义工作流
$graph->addEdge('start', 'middle');
$graph->addEdge('middle', 'end');

// 5. 设置入口和终点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 6. 编译并运行图
$runnable = $graph->compile();
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState);

print_r($finalState->getData());
// 输出: [ 'value' => 1, 'message' => '从起点开始！ ... 现在到达中间点！ ... 完成！' ]
```

### 2. 条件逻辑

您可以使用条件边根据当前状态来引导图的流程。这允许实现动态的、可分支的工作流。

```php
// 一个用于决定下一步的函数
$decider = function (MyState $state) {
    if ($state->get('value', 0) > 5) {
        return 'end_workflow';
    } else {
        return 'continue_processing';
    }
};

// 从 'start' 节点添加一个条件边
$graph->addConditionalEdges('start', $decider, [
    'end_workflow' => 'end',
    'continue_processing' => 'middle',
]);
```

### 3. 使用 AI 智能体

SDK 包含一个 `AgentFactory`，可以轻松创建基于模型的智能体，并将其用作图中的节点。

首先，在 `config/model.php` 中配置您的模型 API 密钥。

```php
use LangGraph\Agent\AgentFactory;
use LangGraph\Model\Factory\ModelFactory;
use LangGraph\Model\Config\ModelConfig;

// 设置工厂
$modelConfig = new ModelConfig(require 'config/model.php');
$modelFactory = new ModelFactory($modelConfig->all());
$agentFactory = new AgentFactory($modelFactory);

// 创建一个智能体
$researcherAgent = $agentFactory->createModelBasedAgent(
    'researcher',
    'deepseek', // 或其他模型，如 'qwen'
    '你是一个乐于助人的研究助理。'
);

// 使用智能体处理查询
$response = $researcherAgent->execute('什么是 LangGraph？');
echo $response;
```

### 4. 使用检查点进行持久化

在工作流的任何时刻保存状态，并在之后恢复。SDK 包含一个用于简单场景的 `MemoryCheckpointSaver`。

```php
use LangGraph\UnifiedGraph\Checkpoint\MemoryCheckpointSaver;

// 在图上设置一个检查点保存器
$checkpointSaver = new MemoryCheckpointSaver();
$graph->setCheckpointSaver($checkpointSaver);

$runnable = $graph->compile();

// 使用唯一 ID 运行工作流以启用检查点
$threadId = 'user_session_123';
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState, $threadId);

// 现在您可以获取此线程的检查点
$checkpoints = $checkpointSaver->list($threadId);
```

## 📚 文档

有关详细文档和常见问题解答，请参阅我们的文档：

- [中文文档](docs/zh/README.md) - 中文文档
- [English Documentation](docs/en/README.md) - 英文文档

## 高级示例

有关更复杂的示例，包括多智能体协作、流式响应和 Web 集成，请浏览 `bin/` 和 `examples/` 目录中的脚本。

## 🤝 贡献

欢迎参与贡献！详情请阅读 `CONTRIBUTING.md`。

## 📄 许可证

本项目采用 MIT 许可证。更多信息请参见 `LICENSE` 文件。