<?php

namespace Nrg\FileManager\Action\File;

use Nrg\FileManager\UseCase\File\UpdateFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class UpdateFileAction.
 *
 * Updates a file with a path and a contents.
 */
class UpdateFileAction implements Observer
{
    use ObserverStub;

    /**
     * @var UpdateFile
     */
    private $updateFile;

    /**
     * @param UpdateFile $updateFile
     */
    public function __construct(UpdateFile $updateFile)
    {
        $this->updateFile = $updateFile;
    }

    /**
     * Updates a file with a path and a contents, contents is optional.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $params = $event->getRequest()->getBodyParams();

        $event->getResponse()
            ->setBody($this->updateFile->execute($params))
            ->setStatus(new HttpStatus(HttpStatus::OK));
    }
}
