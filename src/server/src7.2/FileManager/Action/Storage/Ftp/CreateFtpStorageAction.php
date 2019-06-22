<?php

namespace Nrg\FileManager\Action\Storage\Ftp;

use Exception;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Form\Storage\Ftp\CreateFtpStorageForm;
use Nrg\FileManager\UseCase\Storage\CreateStorage;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CreateFtpStorageAction
 *
 * Creates a Ftp storage.
 */
class CreateFtpStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var CreateFtpStorageForm
     */
    private $form;

    /**
     * @var CreateStorage
     */
    private $createStorage;

    /**
     * @param CreateFtpStorageForm $form
     * @param CreateStorage $createStorage
     */
    public function __construct(CreateFtpStorageForm $form, CreateStorage $createStorage)
    {
        $this->form = $form;
        $this->createStorage = $createStorage;
    }

    /**
     * Creates a Ftp storage.
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
                ->setBody($this->createStorage->execute($this->form->serialize(), Storage::TYPE_FTP))
                ->setStatus(new HttpStatus(HttpStatus::CREATED));
        }
    }
}
