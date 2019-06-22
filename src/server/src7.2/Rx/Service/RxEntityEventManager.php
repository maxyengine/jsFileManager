<?php

namespace Nrg\Rx\Service;

use Nrg\Rx\Abstraction\EntityEventManager;
use Nrg\Rx\Abstraction\EventEntity;
use Nrg\Rx\Abstraction\EventProvider;

/**
 * Entity event manager implementation.
 *
 * Class RxEntityEventManager
 */
class RxEntityEventManager implements EntityEventManager
{
    /**
     * @var EventProvider
     */
    private $eventProvider;

    /**
     * @param EventProvider $eventProvider
     */
    public function __construct(EventProvider $eventProvider)
    {
        $this->eventProvider = $eventProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function process(EventEntity $entity)
    {
        foreach ($entity->getEvents() as $event) {
            $this->eventProvider->trigger($event);
        }
        $this->clear($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(EventEntity $entity)
    {
        $entity->clearEvents();
    }
}
