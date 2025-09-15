<?php

namespace App\Agent;

use App\LangGraph\State\GraphState;

class ResponseAgent extends BaseAgent
{
    private $response;
    
    public function __construct(string $name, string $response, string $description = '')
    {
        parent::__construct($name, $description);
        $this->response = $response;
    }
    
    protected function process(GraphState $state): GraphState
    {
        // 设置响应
        $state->set('agent_response', $this->response);
        $state->set('agent_' . $this->name . '_response', $this->response);
        
        return $state;
    }
}