<?php

namespace Nrg\FileManager\Action\File;

use Nrg\FileManager\Form\File\CopyFileForm;
use Nrg\FileManager\UseCase\File\CopyFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CopyFileAction.
 *
 * Copies a file.
 */
class CopyFileAction implements Observer
{
    use ObserverStub;

    /**
     * @var CopyFileForm
     */
    private $form;

    /**
     * @var CopyFileAction
     */
    private $copyFile;

    /**
     * @param CopyFile $copyFile
     */
    public function __construct(CopyFileForm $form, CopyFile $copyFile)
    {
        $this->form = $form;
        $this->copyFile = $copyFile;
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
            $this->copyFile->execute($this->form->serialize());
            $event->getResponse()->setStatus(new HttpStatus(HttpStatus::NO_CONTENT));
        }
    }
}
