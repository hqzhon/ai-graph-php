<?php

namespace App\Controller;

use App\Http\Response;
use App\Model\Article;
use App\Service\ServiceContainer;
use App\View\Template;

class ArticleController
{
    private $template;
    private $workflow;
    
    public function __construct()
    {
        $this->template = new Template(__DIR__ . '/../../templates');
        $this->workflow = ServiceContainer::get('article_workflow');
    }
    
    public function create()
    {
        // 创建新文章
        $article = new Article('New Article', 'This is the content of the new article.');
        
        // 显示文章创建页面
        $content = $this->template->render('article_create', [
            'title' => 'Create Article',
            'article' => $article
        ]);
        
        return new Response($content);
    }
    
    public function view($id)
    {
        // 在实际应用中，这里会从数据库获取文章
        // 为了演示，我们创建一个示例文章
        $article = new Article('Sample Article', 'This is a sample article content.');
        
        // 根据ID设置不同的状态
        switch ($id) {
            case 1:
                $article->setStatus('draft');
                break;
            case 2:
                $article->setStatus('review');
                break;
            case 3:
                $article->setStatus('published');
                break;
            case 4:
                $article->setStatus('rejected');
                break;
            default:
                $article->setStatus('draft');
        }
        
        // 获取当前状态下的可用转换
        $transitions = $this->workflow->getWorkflow()->getEnabledTransitions($article);
        $availableTransitions = [];
        foreach ($transitions as $transition) {
            $availableTransitions[] = $transition->getName();
        }
        
        // 显示文章详情页面
        $content = $this->template->render('article_view', [
            'title' => 'View Article',
            'article' => $article,
            'availableTransitions' => $availableTransitions,
            'id' => $id
        ]);
        
        return new Response($content);
    }
    
    public function transition($id, $transition)
    {
        // 在实际应用中，这里会从数据库获取文章
        // 为了演示，我们创建一个示例文章
        $article = new Article('Sample Article', 'This is a sample article content.');
        
        // 根据ID设置不同的状态
        switch ($id) {
            case 1:
                $article->setStatus('draft');
                break;
            case 2:
                $article->setStatus('review');
                break;
            case 3:
                $article->setStatus('published');
                break;
            case 4:
                $article->setStatus('rejected');
                break;
            default:
                $article->setStatus('draft');
        }
        
        // 检查转换是否可用
        if ($this->workflow->can($article, $transition)) {
            // 应用转换
            $this->workflow->apply($article, $transition);
            
            // 返回成功消息
            $content = $this->template->render('article_transition_success', [
                'title' => 'Transition Success',
                'article' => $article,
                'transition' => $transition
            ]);
        } else {
            // 返回错误消息
            $content = $this->template->render('article_transition_error', [
                'title' => 'Transition Error',
                'article' => $article,
                'transition' => $transition
            ]);
        }
        
        return new Response($content);
    }
}