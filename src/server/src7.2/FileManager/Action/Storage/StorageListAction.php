<?php

namespace Nrg\FileManager\Action\Storage;

use Nrg\FileManager\UseCase\Storage\StorageList;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class StorageListAction
 */
class StorageListAction implements Observer
{
    use ObserverStub;

    /**
     * @var StorageList
     */
    private $storageList;

    /**
     * @param StorageList $storageList
     */
    public function __construct(StorageList $storageList)
    {
        $this->storageList = $storageList;
    }

    /**
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $event->getResponse()
            ->setStatus(new HttpStatus(HttpStatus::OK))
            ->setBody($this->storageList->execute($event->getRequest()->getBodyParams()));
    }
}
