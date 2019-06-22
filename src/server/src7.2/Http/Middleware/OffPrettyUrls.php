<?php

namespace Nrg\Http\Middleware;

use Nrg\Http\Event\HttpExchangeEvent;

class OffPrettyUrls
{
    private const ROUTE_VAR_NAME = 'r';
    private const DEFAULT_ROUTE = '/';

    /**
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $url = $event->getRequest()->getUrl();
        $url->setPath($url->getQueryParam(self::ROUTE_VAR_NAME, self::DEFAULT_ROUTE));
    }
}
