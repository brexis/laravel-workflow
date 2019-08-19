<?php

namespace Vimily\LaravelWorkflow\Events;

use Symfony\Component\Workflow\Event\Event;

/**
 * @author Boris Koumondji <Vimily@yahoo.fr>
 */
abstract class BaseEvent
{
    /**
     * @var Event
     */
    protected $originalEvent;

    public function __construct(Event $event)
    {
        $this->originalEvent = $event;
    }

    /**
     * Return the original event
     * @return Event
     */
    public function getOriginalEvent()
    {
        return $this->originalEvent;
    }
}
