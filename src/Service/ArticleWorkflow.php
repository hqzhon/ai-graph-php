<?php

namespace App\Service;

use App\Model\Article;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\StateMachine;

class ArticleWorkflow
{
    private $workflow;
    
    public function __construct()
    {
        // 定义工作流
        $definitionBuilder = new DefinitionBuilder();
        $definition = $definitionBuilder
            ->addPlaces(['draft', 'review', 'published', 'rejected'])
            ->addTransition(new Transition('request_review', 'draft', 'review'))
            ->addTransition(new Transition('publish', 'review', 'published'))
            ->addTransition(new Transition('reject', 'review', 'rejected'))
            ->addTransition(new Transition('rework', 'rejected', 'draft'))
            ->build();
        
        // 状态存储方式
        $marking = new MethodMarkingStore(true, 'status');
        
        // 创建状态机 (修复参数)
        $this->workflow = new StateMachine($definition, $marking);
    }
    
    public function getWorkflow()
    {
        return $this->workflow;
    }
    
    public function can($article, $transition)
    {
        return $this->workflow->can($article, $transition);
    }
    
    public function apply($article, $transition)
    {
        return $this->workflow->apply($article, $transition);
    }
    
    public function getMarking($article)
    {
        return $this->workflow->getMarking($article);
    }
}