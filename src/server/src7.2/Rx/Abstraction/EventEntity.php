<?php

namespace Nrg\Rx\Abstraction;

interface EventEntity
{
    /**
     * @return array
     */
    public function getEvents(): array;

    /**
     * @param $event
     */
    public function addEvent($event);

    /**
     * @return mixed
     */
    public function clearEvents();
}
