<?php

namespace Nrg\Rx\Abstraction;

/**
 * Interface EntityEventManager.
 */
interface EntityEventManager
{
    /**
     * Handles entities events.
     *
     * @param EventEntity $entity
     */
    public function process(EventEntity $entity);

    /**
     * Clears entities events.
     *
     * @param EventEntity $entity
     */
    public function clear(EventEntity $entity);
}
