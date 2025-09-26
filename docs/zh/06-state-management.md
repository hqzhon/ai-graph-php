# LangGraph PHP SDK FAQ - 状态管理

## 什么是通道（Channels）？
通道是状态管理的机制，类似于Python LangGraph中的通道。它们定义了状态如何更新和合并。

## 支持哪些类型的通道？
1. **LastValueChannel**: 保留最后一个值
2. **BinaryOperatorAggregateChannel**: 使用二元操作符合并值

## 如何使用通道？
```php
$graph = new StateGraph(ChannelsState::class);

// 添加通道定义
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
```