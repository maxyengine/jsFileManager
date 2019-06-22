<?php

namespace Nrg\Http\Middleware;

use Nrg\Http\Event\HttpExchangeEvent;

/**
 * Class ParseJsonRequest
 */
class ParseJsonRequest
{
    /**
     * Handles JSON request.
     * Sets request body params from decoded request body.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        //TODO : add checking json error
        $bodyParams = json_decode($request->getBody(), true) ?? [];
        $request->setBodyParams($bodyParams + $request->getBodyParams());
        $response->setHeader('Content-Type', 'application/json; charset=utf-8');
    }
}
