<?php

namespace Nrg\FileManager\Action\Storage\Sftp;

use Exception;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Form\Storage\Sftp\CreateSftpStorageForm;
use Nrg\FileManager\UseCase\Storage\CreateStorage;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CreateSftpStorageAction
 *
 * Creates a Sftp storage.
 */
class CreateSftpStorageAction implements Observer
{
    use ObserverStub;

    /**
     * @var CreateSftpStorageForm
     */
    private $form;

    /**
     * @var CreateStorage
     */
    private $createStorage;

    /**
     * @param CreateSftpStorageForm $form
     * @param CreateStorage $createSftpStorage
     */
    public function __construct(CreateSftpStorageForm $form, CreateStorage $createSftpStorage)
    {
        $this->form = $form;
        $this->createStorage = $createSftpStorage;
    }

    /**
     * Creates a Sftp storage.
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
                ->setBody($this->createStorage->execute($this->form->serialize(), Storage::TYPE_SFTP))
                ->setStatus(new HttpStatus(HttpStatus::CREATED));
        }
    }
}
