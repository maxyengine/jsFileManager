<?php

namespace Nrg\Rx\Service;

use SplObjectStorage;
use Throwable;

/**
 * Class Observable.
 *
 * Event observable implementation.
 */
class Observable
{
    /**
     * @var array
     */
    private $storage;

    /**
     * Observable constructor.
     */
    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    /**
     * @param object $observer
     *
     * @return Observable
     */
    public function addObserver($observer): Observable
    {
        $this->storage->attach($observer);

        return $this;
    }

    /**
     * @param object $observer
     *
     * @return Observable
     */
    public function removeObserver($observer): Observable
    {
        $this->storage->detach($observer);

        return $this;
    }

    /**
     * Notifies observers about event.
     *
     * @param $event
     */
    public function notifyObservers($event)
    {
        try {
            foreach ($this->storage as $observer) {
                if (method_exists($observer, 'onNext')) {
                    $observer->onNext($event);
                }
            }
            foreach ($this->storage as $observer) {
                if (method_exists($observer, 'onComplete')) {
                    $observer->onComplete();
                }
            }
        } catch (Throwable $throwable) {
            foreach ($this->storage as $observer) {
                if (method_exists($observer, 'onError')) {
                    $observer->onError($throwable, $event);
                }
            }
        }
    }
}
