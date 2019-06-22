<?php

namespace Nrg\Auth\Action;

use Nrg\Auth\UseCase\CreateLoginOutput;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Exception;

/**
 * Class RefreshLoginAction
 */
class RefreshLoginAction
{
    /**
     * @var CreateLoginOutput
     */
    private $createLoginOutput;

    /**
     * @param CreateLoginOutput $createLoginOutput
     */
    public function __construct(CreateLoginOutput $createLoginOutput)
    {
        $this->createLoginOutput = $createLoginOutput;
    }

    /**
     * @param HttpExchangeEvent $event
     *
     * @throws Exception
     */
    public function onNext(HttpExchangeEvent $event)
    {
        $event->getResponse()
            ->setStatusCode(HttpStatus::CREATED)
            ->setBody($this->createLoginOutput->execute());
    }
}
