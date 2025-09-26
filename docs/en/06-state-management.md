# LangGraph PHP SDK FAQ - State Management

## What are channels?
Channels are a mechanism for state management, similar to channels in Python's LangGraph. They define how state is updated and merged.

## What types of channels are supported?
1. **LastValueChannel**: Retains the last value
2. **BinaryOperatorAggregateChannel**: Merges values using a binary operator

## How to use channels?
```php
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
```