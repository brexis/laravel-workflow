<?php

namespace Tests {

    /**
     * Class TestCase
     *
     * @package Tests
     */
    abstract class TestCase extends \PHPUnit\Framework\TestCase
    {

    }
}

namespace {

    if (! function_exists('event')) {
        $events = null;

        function event($ev)
        {
            global $events;
            $events[] = $ev;
        }
    }
}
