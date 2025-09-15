<?php

namespace App\Agent\Communication;

class Message
{
    private $sender;
    private $recipient;
    private $content;
    private $timestamp;
    private $type;
    
    public function __construct(
        string $sender, 
        string $recipient, 
        string $content, 
        string $type = 'text'
    ) {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->content = $content;
        $this->type = $type;
        $this->timestamp = microtime(true);
    }
    
    public function getSender(): string
    {
        return $this->sender;
    }
    
    public function getRecipient(): string
    {
        return $this->recipient;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function getTimestamp(): float
    {
        return $this->timestamp;
    }
    
    public function getType(): string
    {
        return $this->type;
    }
}