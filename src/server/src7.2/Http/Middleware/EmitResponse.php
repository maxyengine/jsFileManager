<?php

namespace Nrg\Http\Middleware;

use Nrg\Http\Abstraction\ResponseEmitter;
use Nrg\Http\Event\HttpExchangeEvent;
use Throwable;

/**
 * Class EmitResponse.
 *
 * Emits the HTTP response when the HttpExchangeEvent are triggered by observable.
 */
class EmitResponse
{
    /**
     * @var ResponseEmitter
     */
    private $emitResponse;

    /**
     * ResponseEmitter constructor.
     *
     * @param ResponseEmitter $emitResponse
     */
    public function __construct(ResponseEmitter $emitResponse)
    {
        $this->emitResponse = $emitResponse;
    }

    /**
     * Emits the HTTP response when the HttpExchangeEvent are triggered by observable.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext(HttpExchangeEvent $event)
    {
        $this->emitResponse->emit($event->getResponse());
    }

    /**
     * Emits the HTTP response when an error occurred while processing the observers.
     *
     * @param Throwable $throwable
     * @param HttpExchangeEvent $event
     */
    public function onError(Throwable $throwable, HttpExchangeEvent $event)
    {
        $this->emitResponse->emit($event->getResponse());
    }
}
