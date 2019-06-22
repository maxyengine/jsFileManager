<?php

namespace Nrg\Rx\Service;

use LogicException;
use Nrg\Di\Abstraction\Injector;
use Nrg\Rx\Abstraction\EventProvider;
use Nrg\Utility\Abstraction\Settings;
use ReflectionException;

/**
 * Class RxEventProvider.
 *
 * Event provider implementation.
 */
class RxEventProvider implements EventProvider
{
    /**
     * @var array
     */
    private $observables = [];

    /**
     * @var Injector
     */
    private $injector;

    /**
     * @param Injector $injector
     * @param Settings $settings
     */
    public function __construct(Injector $injector, Settings $settings)
    {
        $this->injector = $injector;

        foreach ($settings->getEvents() as $event => $observers) {
            $this->on($event, $observers);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event)
    {
        $observable = $this->getObservable(get_class($event));

        if (null === $observable) {
            throw new LogicException('Event \''.get_class($event).'\' is not registered');
        }

        $observable->notifyObservers($event);
    }

    /**
     * Adds the observers for the event.
     *
     * @param string $event
     * @param array $observers
     */
    private function on(string $event, array $observers)
    {
        $this->observables[$event] = $observers;
    }

    /**
     * Returns the observable corresponding to the event.
     *
     * @param string $event
     *
     * @return null|Observable
     * @throws ReflectionException
     *
     */
    private function getObservable(string $event)
    {
        if (!isset($this->observables[$event])) {
            return null;
        }

        if (is_object($this->observables[$event])) {
            return $this->observables[$event];
        }

        $observable = new Observable();
        foreach ($this->observables[$event] as $index => $observer) {
            $observer = $this->injector->createObjectByDefinition($observer);
            $observable->addObserver($observer);
        }
        $this->observables[$event] = $observable;

        return $this->observables[$event];
    }
}
