<?php

namespace Nrg\Http\Middleware;

use Nrg\Http\Abstraction\RouteProvider;
use Nrg\Http\Event\HttpExchangeEvent;
use ReflectionException;
use Throwable;

/**
 * Class RunAction.
 *
 * Manages the launch of the corresponding routing action when the HttpExchangeEvent are triggered by observable.
 */
class RunAction
{
    /**
     * @var RouteProvider
     */
    private $routeProvider;

    /**
     * @var null|object
     */
    private $action;

    /**
     * @param RouteProvider $routeProvider
     */
    public function __construct(RouteProvider $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * Runs a corresponding routing action when the HttpExchangeEvent are triggered by observable.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws ReflectionException
     */
    public function onNext($event)
    {
        $this->action = $this->routeProvider->getAction();
        if (method_exists($this->action, 'onNext')) {
            $this->action->onNext($event);
        }
    }

    /**
     * Runs error handler on corresponding routing action.
     *
     * @param Throwable $throwable
     * @param HttpExchangeEvent $event
     *
     * @throws Throwable
     */
    public function onError(Throwable $throwable, $event)
    {
        if (null !== $this->action && method_exists($this->action, 'onError')) {
            $this->action->onError($throwable, $event);
        }
    }

    /**
     * Runs completion handler on corresponding routing action.
     */
    public function onComplete()
    {
        if (null !== $this->action && method_exists($this->action, 'onComplete')) {
            $this->action->onComplete();
        }
    }
}
