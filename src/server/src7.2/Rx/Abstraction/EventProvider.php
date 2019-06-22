<?php

namespace Nrg\Rx\Abstraction;

use ReflectionException;

/**
 * Interface EventProvider.
 */
interface EventProvider
{
    /**
     * Trigger event.
     *
     * Notify observers about event.
     *
     * @param $event
     *
     * @throws ReflectionException
     */
    public function trigger($event);
}
