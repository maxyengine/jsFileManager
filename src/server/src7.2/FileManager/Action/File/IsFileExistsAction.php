<?php

namespace Nrg\FileManager\Action\File;

use Nrg\Http\Value\HttpStatus;
use Nrg\FileManager\UseCase\File\IsFileExists;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class IsFileExistsAction.
 *
 * Checks if a file with a path exists.
 */
class IsFileExistsAction implements Observer
{
    use ObserverStub;

    /**
     * @var IsFileExists
     */
    private $isFileExists;

    /**
     * @param IsFileExists $isFileExists
     */
    public function __construct(IsFileExists $isFileExists)
    {
        $this->isFileExists = $isFileExists;
    }

    /**
     * Reads a file by a path.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $params = $event->getRequest()->getBodyParams();

        $event->getResponse()
            ->setBody($this->isFileExists->execute($params))
            ->setStatus(new HttpStatus(HttpStatus::OK));
    }
}
