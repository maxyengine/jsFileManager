<?php

namespace Nrg\Http\Middleware;

use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpResponse;
use Throwable;

/**
 * Class SerializeJsonResponse
 */
class SerializeJsonResponse
{
    /**
     * Handles JSON response on ExchangeEvent process.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->execute($event->getResponse());
    }

    /**
     * @param Throwable $throwable
     * @param HttpExchangeEvent $event
     *
     * @throws Throwable
     */
    public function onError(Throwable $throwable, $event)
    {
        $this->execute($event->getResponse());
    }

    /**
     * @param HttpResponse $response
     */
    private function execute(HttpResponse $response)
    {
        if ($response->containsInHeader('Content-Type', 'application/json')) {
            $response->setHeader('Content-Type', 'application/json; charset=utf-8');
            $response->setBody(
                json_encode($response->getBody(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }
    }
}
