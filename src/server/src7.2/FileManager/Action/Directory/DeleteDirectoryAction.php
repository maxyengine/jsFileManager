<?php

namespace Nrg\FileManager\Action\Directory;

use Nrg\FileManager\UseCase\Directory\DeleteDirectory;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class DeleteDirectoryAction.
 *
 * Deletes a directory.
 */
class DeleteDirectoryAction implements Observer
{
    use ObserverStub;

    /**
     * @var DeleteDirectoryAction
     */
    private $deleteDirectory;

    /**
     * @param DeleteDirectory $deleteDirectory
     */
    public function __construct(DeleteDirectory $deleteDirectory)
    {
        $this->deleteDirectory = $deleteDirectory;
    }

    /**
     * Copies a file.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $params = $event->getRequest()->getBodyParams();

        $this->deleteDirectory->execute($params);

        $event->getResponse()->setStatus(
            new HttpStatus(HttpStatus::NO_CONTENT)
        );
    }
}
