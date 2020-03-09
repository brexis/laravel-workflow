<?php
namespace Tests\Fixtures;

class TestObject
{
    public $marking;

    /**
     * @return mixed
     */
    public function getMarking()
    {
        return $this->marking;
    }

    /**
     * @param  mixed  $marking
     */
    public function setMarking($marking)
    {
        $this->marking = $marking;
    }
}
