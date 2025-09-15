<?php

namespace App\Model;

class Article
{
    private $title;
    private $content;
    private $status;
    
    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
        $this->status = 'draft'; // 初始状态为草稿
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
    }
}