<?php

namespace Nrg\FileManager\Action\File;

use Nrg\FileManager\Form\File\MoveFileForm;
use Nrg\FileManager\UseCase\File\MoveFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class MoveFileAction.
 *
 * Copies a file.
 */
class MoveFileAction implements Observer
{
    use ObserverStub;

    /**
     * @var MoveFileAction
     */
    private $moveFile;
    /**
     * @var MoveFileForm
     */
    private $form;

    /**
     * @param MoveFile $moveFile
     */
    public function __construct(MoveFileForm $form, MoveFile $moveFile)
    {
        $this->form = $form;
        $this->moveFile = $moveFile;
    }

    /**
     * Copies a file.
     *
     * @param HttpExchangeEvent $event
     */
    public function onNext($event)
    {
        $this->form->populate($event->getRequest()->getBodyParams());

        if ($this->form->hasErrors()) {
            $event->getResponse()
                ->setStatus(new HttpStatus(HttpStatus::UNPROCESSABLE_ENTITY))
                ->setBody($this->form->serialize());
        } else {
            $this->moveFile->execute($this->form->serialize());
            $event->getResponse()->setStatus(new HttpStatus(HttpStatus::NO_CONTENT));
        }
    }
}
