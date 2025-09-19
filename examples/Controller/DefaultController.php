<?php

namespace App\Examples\Controller;

use App\Examples\Http\Response;
use App\View\Template;

class DefaultController
{
    private $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }
    
    public function index()
    {
        $content = $this->template->render('default', [
            'title' => 'Welcome to PHP MVP'
        ]);
        
        return new Response($content);
    }
    
    public function notFound()
    {
        $content = $this->template->render('default', [
            'title' => 'Not Found',
            'heading' => '404 - Page Not Found',
            'content' => 'The requested page could not be found.'
        ]);
        
        return new Response($content, 404);
    }
}