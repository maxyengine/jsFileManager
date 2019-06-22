<?php

namespace Nrg\FileManager\Action\File;

use Nrg\FileManager\UseCase\File\CreateFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CreateFileAction.
 *
 * Creates a file with a path and a contents, the contents is optional.
 */
class CreateFileAction implements Observer
{
    use ObserverStub;

    /**
     * @var CreateFile
     */
    private $createFile;

    /**
     * @param CreateFile $createFile
     */
    public function __construct(CreateFile $createFile)
    {
        $this->createFile = $createFile;
    }

    /**
     * Creates a file with a path and a contents, contents is optional.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $params = $event->getRequest()->getBodyParams();

        $event->getResponse()
            ->setBody($this->createFile->execute($params))
            ->setStatus(new HttpStatus(HttpStatus::CREATED));
    }
}
