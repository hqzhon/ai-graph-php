<?php

namespace App\Examples\Middleware;

class Middleware
{
    protected $next;
    
    public function setNext(Middleware $next)
    {
        $this->next = $next;
        return $next;
    }
    
    public function handle($request)
    {
        if ($this->next) {
            return $this->next->handle($request);
        }
        
        return null;
    }
}