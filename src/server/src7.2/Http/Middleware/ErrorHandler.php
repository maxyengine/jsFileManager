<?php

namespace Nrg\Http\Middleware;

use DomainException;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Exception\HttpException;
use Nrg\Http\Exception\ValidationException;
use Nrg\Http\Value\HttpErrorMessage;
use Nrg\Http\Value\HttpStatus;
use Nrg\Utility\Abstraction\Config;
use Throwable;

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
    /**
     * @var string
     */
    private $debug;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->debug = $config->isDevelopmentMode();
    }

    /**
     * @param Throwable $throwable
     * @param HttpExchangeEvent $event
     */
    public function onError(Throwable $throwable, HttpExchangeEvent $event)
    {
        if ($throwable instanceof HttpException) {
            $code = $throwable->getCode();
        } elseif ($throwable instanceof DomainException) {
            $code = HttpStatus::BAD_REQUEST;
        } else {
            $code = HttpStatus::INTERNAL_SERVER_ERROR;
        }

        $status = new HttpStatus($code, $throwable->getMessage());
        $errorMessage = new HttpErrorMessage($status);

        if ($throwable instanceof ValidationException) {
            $errorMessage->setDetails($throwable->getDetails());
        }

        if ($this->debug) {
            $errorMessage->setDebugInfo(
                [
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ]
            );
        }

        $event->getResponse()
            ->setStatus($status)
            ->setBody($errorMessage);
    }
}
