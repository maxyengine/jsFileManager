<?php

namespace Nrg\FileManager\Action\Storage\Local;

use Exception;
use Nrg\FileManager\Form\Storage\Local\UpdateLocalStorageForm;
use Nrg\FileManager\UseCase\Storage\UpdateStorage;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class UpdateLocalStorageAction
 *
 * Updates a local storage.
 */
class UpdateLocalStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var UpdateLocalStorageForm
     */
    private $form;

    /**
     * @var UpdateStorage
     */
    private $updateStorage;

    /**
     * @param UpdateLocalStorageForm $form
     * @param UpdateStorage $updateStorage
     */
    public function __construct(UpdateLocalStorageForm $form, UpdateStorage $updateStorage)
    {
        $this->form = $form;
        $this->updateStorage = $updateStorage;
    }

    /**
     * Updates a local storage.
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
                ->setBody($this->updateStorage->execute($this->form->serialize()))
                ->setStatus(new HttpStatus(HttpStatus::OK));
        }
    }
}
