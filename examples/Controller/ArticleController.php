<?php

namespace App\Examples\Controller;

use App\Examples\Http\Response;
use App\Model\Article;
use App\Service\ArticleWorkflow;
use App\View\Template;

class ArticleController
{
    private $template;
    private $workflow;

    public function __construct(Template $template, ArticleWorkflow $workflow)
    {
        $this->template = $template;
        $this->workflow = $workflow;
    }

    public function demo($id = 1)
    {
        $article = new Article('Sample Article', 'This is a sample article content.');
        $id = (int)$id;

        // This is a simulation. In a real app, you would fetch the article from a database.
        switch ($id) {
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
                $id = 1;
                $article->setStatus('draft');
        }

        $enabledTransitions = $this->workflow->getWorkflow()->getEnabledTransitions($article);
        $availableTransitions = array_map(fn($t) => $t->getName(), $enabledTransitions);

        $content = $this->template->render('article_demo', [
            'title' => 'Article Workflow Demo',
            'article' => $article,
            'availableTransitions' => $availableTransitions,
            'id' => $id
        ]);

        return new Response($content);
    }

    public function transition($id, $transition)
    {
        $article = new Article('Sample Article', 'This is a sample article content.');
        $id = (int)$id;

        // Simulate fetching the correct article state
        $statusMap = [1 => 'draft', 2 => 'review', 3 => 'published', 4 => 'rejected'];
        $article->setStatus($statusMap[$id] ?? 'draft');

        if ($this->workflow->can($article, $transition)) {
            $this->workflow->apply($article, $transition);
        }

        // Find the new ID based on the new status
        $newStatus = $article->getStatus();
        $newId = array_search($newStatus, $statusMap);
        if ($newId === false) $newId = 1;

        // Redirect to the demo page for the new state
        return new Response(
            '',
            302,
            ['Location' => '/article-demo/' . $newId]
        );
    }
}
