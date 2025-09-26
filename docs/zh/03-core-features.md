# LangGraph PHP SDK FAQ - 核心功能

## 什么是StateGraph？
StateGraph是LangGraph PHP SDK的基本构建块。它允许您定义状态类，添加节点（函数或可调用对象），然后用边将它们连接起来形成工作流。

## 如何创建基本工作流？
```php
use LangGraph\UnifiedGraph\StateGraph;
use LangGraph\UnifiedGraph\State\State;

// 1. 定义图的状态结构
class MyState extends State {
    // 您的状态属性
}

// 2. 使用状态类初始化图
$graph = new StateGraph(MyState::class);

// 3. 添加节点
$graph->addNode('start', function (MyState $state) {
    echo "执行节点: start\n";
    $state->set('message', '从起点开始！');
    return $state;
});

$graph->addNode('end', function (MyState $state) {
    echo "执行节点: end\n";
    $state->set('message', $state->get('message') . ' ... 完成！');
    return $state;
});

// 4. 添加边
$graph->addEdge('start', 'end');

// 5. 设置入口和终点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 6. 编译并运行图
$runnable = $graph->compile();
$initialState = new MyState(['value' => 1]);
$finalState = $runnable->execute($initialState);
```

## 如何实现条件逻辑？
您可以使用条件边根据当前状态来引导图的流程：

```php
// 决定下一步的函数
$decider = function (MyState $state) {
    if ($state->get('value', 0) > 5) {
        return 'end_workflow';
    } else {
        return 'continue_processing';
    }
};

// 从'start'节点添加条件边
$graph->addConditionalEdges('start', $decider, [
    'end_workflow' => 'end',
    'continue_processing' => 'middle',
]);
```