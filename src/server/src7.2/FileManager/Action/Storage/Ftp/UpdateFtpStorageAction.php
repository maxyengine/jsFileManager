<?php

namespace Nrg\FileManager\Action\Storage\Ftp;

use Exception;
use Nrg\FileManager\Form\Storage\Ftp\UpdateFtpStorageForm;
use Nrg\FileManager\UseCase\Storage\UpdateStorage;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class UpdateFtpStorageAction
 *
 * Updates a ftp storage.
 */
class UpdateFtpStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var UpdateFtpStorageForm
     */
    private $form;

    /**
     * @var UpdateStorage
     */
    private $updateStorage;

    /**
     * @param UpdateFtpStorageForm $form
     * @param UpdateStorage $updateStorage
     */
    public function __construct(UpdateFtpStorageForm $form, UpdateStorage $updateStorage)
    {
        $this->form = $form;
        $this->updateStorage = $updateStorage;
    }

    /**
     * Updates a ftp storage.
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
