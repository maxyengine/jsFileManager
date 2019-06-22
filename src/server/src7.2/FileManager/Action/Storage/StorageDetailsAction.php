<?php

namespace Nrg\FileManager\Action\Storage;

use Exception;
use Nrg\FileManager\UseCase\Storage\StorageDetails;
use Nrg\Form\Form\RequiredUuidForm;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class StorageDetailsAction
 *
 * Deletes a storage.
 */
class StorageDetailsAction implements Observer
{
    use ObserverStub;

    /**
     * @var RequiredUuidForm
     */
    private $form;

    /**
     * @var StorageDetails
     */
    private $storageDetails;

    public function __construct(RequiredUuidForm $form, StorageDetails $storageDetails)
    {
        $this->form = $form;
        $this->storageDetails = $storageDetails;
    }

    /**
     * Deletes a  storage.
     *
     * @param HttpExchangeEvent $event
     * @throws Exception
     */
    public function onNext($event)
    {
        $this->form->populate($event->getRequest()->getBodyParams());

        if ($this->form->hasErrors()) {
            $event->getResponse()
                ->setBody($this->form->serialize())
                ->setStatus(new HttpStatus(HttpStatus::UNPROCESSABLE_ENTITY));
        } else {
            $event->getResponse()
                ->setBody($this->storageDetails->execute($this->form->serialize()))
                ->setStatus(new HttpStatus(HttpStatus::OK));
        }
    }
}
