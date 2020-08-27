<?php

namespace Tests;

use Brexis\LaravelWorkflow\Events\CompletedEvent;
use Brexis\LaravelWorkflow\Events\EnteredEvent;
use Brexis\LaravelWorkflow\Events\EnterEvent;
use Brexis\LaravelWorkflow\Events\GuardEvent;
use Brexis\LaravelWorkflow\Events\LeaveEvent;
use Brexis\LaravelWorkflow\Events\TransitionEvent;
use Brexis\LaravelWorkflow\WorkflowRegistry;
use Tests\Fixtures\TestObject;

class WorkflowSubscriberTest extends TestCase
{
    public function testIfWorkflowEmitsEvents()
    {
        global $events;

        $events = [];

        $config = [
            'straight' => [
                'supports'    => [TestObject::class],
                'places'      => ['a', 'b', 'c'],
                'transitions' => [
                    't1' => [
                        'from' => 'a',
                        'to'   => 'b',
                    ],
                    't2' => [
                        'from' => 'b',
                        'to'   => 'c',
                    ],
                ],
            ],
        ];

        $registry = new WorkflowRegistry($config);
        $object = new TestObject;
        $workflow = $registry->get($object);

        $workflow->apply($object, 't1');

        $this->assertCount(31, $events);

        $this->assertInstanceOf(EnteredEvent::class, $events[0]);
        $this->assertEquals('workflow.entered', $events[1]);
        $this->assertEquals('workflow.straight.entered', $events[2]);

        $this->assertInstanceOf(GuardEvent::class, $events[3]);
        $this->assertEquals('workflow.guard', $events[4]);
        $this->assertEquals('workflow.straight.guard', $events[5]);
        $this->assertEquals('workflow.straight.guard.t1', $events[6]);

        $this->assertInstanceOf(LeaveEvent::class, $events[7]);
        $this->assertEquals('workflow.leave', $events[8]);
        $this->assertEquals('workflow.straight.leave', $events[9]);
        $this->assertEquals('workflow.straight.leave.a', $events[10]);

        $this->assertInstanceOf(TransitionEvent::class, $events[11]);
        $this->assertEquals('workflow.transition', $events[12]);
        $this->assertEquals('workflow.straight.transition', $events[13]);
        $this->assertEquals('workflow.straight.transition.t1', $events[14]);

        $this->assertInstanceOf(EnterEvent::class, $events[15]);
        $this->assertEquals('workflow.enter', $events[16]);
        $this->assertEquals('workflow.straight.enter', $events[17]);
        $this->assertEquals('workflow.straight.enter.b', $events[18]);

        $this->assertInstanceOf(EnteredEvent::class, $events[19]);
        $this->assertEquals('workflow.entered', $events[20]);
        $this->assertEquals('workflow.straight.entered', $events[21]);
        $this->assertEquals('workflow.straight.entered.b', $events[22]);

        $this->assertInstanceOf(CompletedEvent::class, $events[23]);
        $this->assertEquals('workflow.completed', $events[24]);
        $this->assertEquals('workflow.straight.completed', $events[25]);
        $this->assertEquals('workflow.straight.completed.t1', $events[26]);

        $this->assertInstanceOf(GuardEvent::class, $events[27]);
        $this->assertEquals('workflow.guard', $events[28]);
        $this->assertEquals('workflow.straight.guard', $events[29]);
        $this->assertEquals('workflow.straight.guard.t2', $events[30]);
    }
}
