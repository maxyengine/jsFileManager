<?php

namespace Nrg\Uploader\Action;

use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Utility\Abstraction\Config;

/**
 * Class ConfigAction
 */
class ConfigAction
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Returns app config.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $event->getResponse()
            ->setBody($this->config)
            ->setStatusCode(HttpStatus::OK);
    }
}
