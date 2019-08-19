<?php

namespace Vimily\LaravelWorkflow\Events;

use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent as SymfonyGuardEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Boris Koumondji <Vimily@yahoo.fr>
 */
class WorkflowSubscriber implements EventSubscriberInterface
{
    public function guardEvent(SymfonyGuardEvent $event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new GuardEvent($event));
        event('workflow.guard', $event);
        event(sprintf('workflow.%s.guard', $workflowName), $event);
        event(sprintf('workflow.%s.guard.%s', $workflowName, $transitionName), $event);
    }

    public function leaveEvent(Event $event)
    {
        $places       = $event->getTransition()->getFroms();
        $workflowName = $event->getWorkflowName();

        event(new LeaveEvent($event));
        event('workflow.leave', $event);
        event(sprintf('workflow.%s.leave', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.leave.%s', $workflowName, $place), $event);
        }
    }

    public function transitionEvent(Event $event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new TransitionEvent($event));
        event('workflow.transition', $event);
        event(sprintf('workflow.%s.transition', $workflowName), $event);
        event(sprintf('workflow.%s.transition.%s', $workflowName, $transitionName), $event);
    }

    public function enterEvent(Event $event)
    {
        $places       = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();

        event(new EnterEvent($event));
        event('workflow.enter', $event);
        event(sprintf('workflow.%s.enter', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.enter.%s', $workflowName, $place), $event);
        }
    }

    public function enteredEvent(Event $event)
    {
        $places       = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();

        event(new EnteredEvent($event));
        event('workflow.entered', $event);
        event(sprintf('workflow.%s.entered', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.entered.%s', $workflowName, $place), $event);
        }
    }

    public function completedEvent(Event $event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new CompletedEvent($event));
        event('workflow.completed', $event);
        event(sprintf('workflow.%s.completed', $workflowName), $event);
        event(sprintf('workflow.%s.completed.%s', $workflowName, $transitionName), $event);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.guard'      => ['guardEvent'],
            'workflow.leave'      => ['leaveEvent'],
            'workflow.transition' => ['transitionEvent'],
            'workflow.enter'      => ['enterEvent'],
            'workflow.entered'    => ['enteredEvent'],
            'workflow.completed'  => ['completedEvent'],
        ];
    }
}
