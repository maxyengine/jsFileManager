<?php

namespace Nrg\FileManager\Action\Storage;

use Exception;
use Nrg\FileManager\UseCase\Storage\DeleteStorage;
use Nrg\Form\Form\RequiredUuidForm;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class DeleteStorageAction
 *
 * Deletes a storage.
 */
class DeleteStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var RequiredUuidForm
     */
    private $form;

    /**
     * @var DeleteStorage
     */
    private $deleteStorage;

    public function __construct(RequiredUuidForm $form, DeleteStorage $deleteStorage)
    {
        $this->form = $form;
        $this->deleteStorage = $deleteStorage;
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
            $this->deleteStorage->execute($this->form->serialize());
            $event->getResponse()->setStatus(new HttpStatus(HttpStatus::NO_CONTENT));
        }
    }
}
