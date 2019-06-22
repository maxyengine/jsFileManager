<?php

namespace Nrg\FileManager\Action\Directory;

use Nrg\Http\Value\HttpStatus;
use Nrg\FileManager\UseCase\Directory\ReadDirectory;
use Nrg\Http\Event\HttpExchangeEvent;

/**
 * Class ReadDirectoryAction.
 *
 * Reads a directory by a path.
 */
class ReadDirectoryAction
{
    /**
     * @var ReadDirectory
     */
    private $readDirectory;

    /**
     * @param ReadDirectory $readDirectory
     */
    public function __construct(ReadDirectory $readDirectory)
    {
        $this->readDirectory = $readDirectory;
    }

    /**
     * Reads a directory by a path.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws \Exception
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $params = $event->getRequest()->getBodyParams();

        $event->getResponse()
            ->setBody($this->readDirectory->execute($params))
            ->setStatus(new HttpStatus(HttpStatus::OK));
    }
}
