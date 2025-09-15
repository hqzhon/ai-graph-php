#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\LangGraph\StateGraph;
use App\LangGraph\State\GraphState;

echo "=== Debug LangGraph Test ===\n\n";

$graph = new StateGraph(GraphState::class);

// 添加节点
$graph->addNode('start', function ($state) {
    echo "Executing start node\n";
    return ['step' => 'start'];
});

$graph->addNode('process_a', function ($state) {
    echo "Executing process_a node\n";
    return ['step' => 'process_a'];
});

$graph->addNode('process_b', function ($state) {
    echo "Executing process_b node\n";
    return ['step' => 'process_b'];
});

$graph->addNode('end', function ($state) {
    echo "Executing end node\n";
    return ['step' => 'end', 'completed' => true];
});

// 添加边
$graph->addEdge('start', 'process_a');
$graph->addEdge('process_b', 'end');

// 添加条件边
$graph->addConditionalEdges('process_a', function ($state) {
    echo "Evaluating condition\n";
    return 'path_b';
}, [
    'path_b' => 'process_b'
]);

// 设置起始和结束节点
$graph->setEntryPoint('start');
$graph->setFinishPoint('end');

// 编译图
$compiled = $graph->compile();

// 创建初始状态并执行
$initialState = new GraphState();
$finalState = $compiled->execute($initialState);

$data = $finalState->getData();
echo "Final state: " . print_r($data, true) . "\n";

echo "\n=== Debug Complete ===\n"