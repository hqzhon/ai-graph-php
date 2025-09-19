#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Article;
use App\Service\ArticleWorkflow;

// 创建文章工作流实例
$workflow = new ArticleWorkflow();

// 创建测试文章
$article = new Article('Test Article', 'Test content');

echo "=== Article Workflow Test ===\n";
echo "Initial status: " . $article->getStatus() . "\n";

// 检查可用的转换
$marking = $workflow->getMarking($article);
echo "Available transitions: ";
$transitions = $workflow->getWorkflow()->getEnabledTransitions($article);
if (empty($transitions)) {
    echo "None\n";
} else {
    $transitionNames = array_map(function($t) { return $t->getName(); }, $transitions);
    echo implode(', ', $transitionNames) . "\n";
}

// 应用request_review转换
if ($workflow->can($article, 'request_review')) {
    $workflow->apply($article, 'request_review');
    echo "Applied 'request_review' transition\n";
    echo "New status: " . $article->getStatus() . "\n";
} else {
    echo "Cannot apply 'request_review' transition\n";
}

// 再次检查可用的转换
echo "Available transitions: ";
$transitions = $workflow->getWorkflow()->getEnabledTransitions($article);
if (empty($transitions)) {
    echo "None\n";
} else {
    $transitionNames = array_map(function($t) { return $t->getName(); }, $transitions);
    echo implode(', ', $transitionNames) . "\n";
}

echo "=== Test Complete ===\n";