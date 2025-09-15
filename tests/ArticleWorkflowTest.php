<?php

namespace App\Tests;

use App\Model\Article;
use App\Service\ArticleWorkflow;
use PHPUnit\Framework\TestCase;

class ArticleWorkflowTest extends TestCase
{
    private $workflow;
    
    protected function setUp(): void
    {
        $this->workflow = new ArticleWorkflow();
    }
    
    public function testArticleCanTransitionFromDraftToReview()
    {
        $article = new Article('Test Article', 'Test content');
        
        // 检查初始状态
        $this->assertEquals('draft', $article->getStatus());
        
        // 检查是否可以进行request_review转换
        $this->assertTrue($this->workflow->can($article, 'request_review'));
        
        // 应用转换
        $this->workflow->apply($article, 'request_review');
        
        // 检查新状态
        $this->assertEquals('review', $article->getStatus());
    }
    
    public function testArticleCannotPublishFromDraft()
    {
        $article = new Article('Test Article', 'Test content');
        
        // 检查初始状态
        $this->assertEquals('draft', $article->getStatus());
        
        // 检查是否不能直接发布
        $this->assertFalse($this->workflow->can($article, 'publish'));
    }
    
    public function testArticleWorkflowTransitions()
    {
        $article = new Article('Test Article', 'Test content');
        
        // 从草稿到审核
        $this->assertTrue($this->workflow->can($article, 'request_review'));
        $this->workflow->apply($article, 'request_review');
        $this->assertEquals('review', $article->getStatus());
        
        // 从审核到发布
        $this->assertTrue($this->workflow->can($article, 'publish'));
        $this->workflow->apply($article, 'publish');
        $this->assertEquals('published', $article->getStatus());
    }
}