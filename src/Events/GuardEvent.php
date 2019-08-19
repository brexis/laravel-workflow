<?php

namespace Vimily\LaravelWorkflow\Events;

use Symfony\Component\Workflow\Event\GuardEvent as SymfonyGuardEvent;

/**
 * @author Boris Koumondji <Vimily@yahoo.fr>
 */
class GuardEvent extends BaseEvent
{
    public function __construct(SymfonyGuardEvent $event)
    {
        $this->originalEvent = $event;
    }
}
