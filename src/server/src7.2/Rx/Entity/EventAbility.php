<?php

namespace Nrg\Rx\Entity;

trait EventAbility
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param $event
     */
    public function addEvent($event)
    {
        $this->events[] = $event;
    }

    public function clearEvents()
    {
        $this->events = [];
    }
}
