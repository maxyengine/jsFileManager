<?php

namespace Nrg\FileManager\Action\Storage\Local;

use Exception;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Form\Storage\Local\CreateLocalStorageForm;
use Nrg\FileManager\UseCase\Storage\CreateStorage;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CreateLocalStorageAction
 *
 * Creates a local storage.
 */
class CreateLocalStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var CreateLocalStorageForm
     */
    private $form;

    /**
     * @var CreateStorage
     */
    private $createStorage;

    /**
     * @param CreateLocalStorageForm $form
     * @param CreateStorage $createStorage
     */
    public function __construct(CreateLocalStorageForm $form, CreateStorage $createStorage)
    {
        $this->form = $form;
        $this->createStorage = $createStorage;
    }

    /**
     * Creates a local storage.
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
                ->setBody($this->createStorage->execute($this->form->serialize(), Storage::TYPE_LOCAL))
                ->setStatus(new HttpStatus(HttpStatus::CREATED));
        }
    }
}
