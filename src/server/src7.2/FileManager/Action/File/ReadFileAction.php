<?php

namespace Nrg\FileManager\Action\File;

use Nrg\Http\Value\HttpStatus;
use Nrg\FileManager\UseCase\File\ReadFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class ReadFileAction.
 *
 * Reads a file by a path.
 */
class ReadFileAction implements Observer
{
    use ObserverStub;

    /**
     * @var ReadFile
     */
    private $readFile;

    /**
     * @param ReadFile $readFile
     */
    public function __construct(ReadFile $readFile)
    {
        $this->readFile = $readFile;
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
            ->setBody($this->readFile->execute($params))
            ->setStatus(new HttpStatus(HttpStatus::OK));
    }
}
