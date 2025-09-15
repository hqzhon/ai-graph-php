<?php

namespace App\Tests\Agent;

use App\Agent\Collaboration\BaseCollaborationProtocol;
use App\Agent\Collaboration\AdvancedCollaborationProtocol;
use App\Agent\Collaboration\BaseTaskAllocator;
use App\Agent\Collaboration\SmartTaskAllocator;
use App\Agent\Collaboration\BaseCoordinator;
use App\Agent\Collaboration\AdvancedCoordinator;
use App\Agent\Collaboration\BaseDecisionMechanism;
use App\Agent\Collaboration\AdvancedDecisionMechanism;
use App\Agent\Collaboration\BaseSwarmIntelligence;
use App\Agent\Collaboration\AdvancedSwarmIntelligence;
use App\Agent\ResponseAgent;
use App\LangGraph\State\GraphState;
use PHPUnit\Framework\TestCase;

class AdvancedCollaborationTest extends TestCase
{
    public function testBaseCollaborationProtocol()
    {
        $protocol = new BaseCollaborationProtocol();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $protocol->initialize($agents);
        
        $state = new GraphState();
        $result = $protocol->requestCollaboration($agent1, 'Test request', $state);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('agent2', $result);
    }
    
    public function testAdvancedCollaborationProtocol()
    {
        $protocol = new AdvancedCollaborationProtocol();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $protocol->initialize($agents);
        
        $state = new GraphState();
        $result = $protocol->requestCollaboration($agent1, 'Calculate something', $state);
        
        $this->assertNotNull($result);
    }
    
    public function testTaskAllocator()
    {
        $allocator = new SmartTaskAllocator();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $state = new GraphState();
        $assignedAgent = $allocator->allocateTask('Test task', $agents, $state);
        
        $this->assertContains($assignedAgent, ['agent1', 'agent2']);
    }
    
    public function testCoordinator()
    {
        $coordinator = new AdvancedCoordinator();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $state = new GraphState();
        $updatedState = $coordinator->coordinate($agents, $state);
        
        $this->assertInstanceOf(GraphState::class, $updatedState);
        $this->assertTrue($updatedState->has('coordination_timestamp'));
    }
    
    public function testDecisionMechanism()
    {
        $decisionMechanism = new AdvancedDecisionMechanism();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $state = new GraphState();
        $decision = $decisionMechanism->makeDecision($agents, 'test_decision', $state);
        
        $this->assertNotNull($decision);
    }
    
    public function testSwarmIntelligence()
    {
        $swarmIntelligence = new AdvancedSwarmIntelligence();
        
        $agent1 = new ResponseAgent('agent1', 'Response 1');
        $agent2 = new ResponseAgent('agent2', 'Response 2');
        $agents = [$agent1, $agent2];
        
        $swarmIntelligence->initialize($agents);
        
        $state = new GraphState();
        $updatedState = $swarmIntelligence->simulateSwarmBehavior($agents, $state);
        
        $this->assertInstanceOf(GraphState::class, $updatedState);
        $this->assertTrue($updatedState->has('shared_information'));
        
        $solutions = ['Solution A', 'Solution B'];
        $optimized = $swarmIntelligence->optimizeSolutions($solutions, $state);
        $this->assertNotNull($optimized);
        
        $performance = $swarmIntelligence->evaluatePerformance($agents, $state);
        $this->assertIsArray($performance);
    }
}