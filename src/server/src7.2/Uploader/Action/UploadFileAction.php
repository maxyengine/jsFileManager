<?php

namespace Nrg\Uploader\Action;

use DomainException;
use Exception;
use Nrg\FileManager\UseCase\File\UploadFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Exception\ValidationException;
use Nrg\Http\Value\HttpStatus;
use Nrg\Uploader\Form\UploadFileForm;

/**
 * Class UploadFileAction.
 *
 * Uploads a file to a path.
 */
class UploadFileAction
{
    /**
     * @var UploadFile
     */
    private $uploadFile;

    /**
     * @var UploadFileForm
     */
    private $form;

    /**
     * @param UploadFile $uploadFile
     */
    public function __construct(UploadFileForm $form, UploadFile $uploadFile)
    {
        $this->form = $form;
        $this->uploadFile = $uploadFile;
    }

    /**
     * Uploads a file to a path.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws Exception
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $this->checkLastError();

        $this->form->populate(
            $event->getRequest()->getUploadedFiles() +
            $event->getRequest()->getBodyParams()
        );

        if ($this->form->hasErrors()) {
            throw new ValidationException($this->form->getErrors());
        }

        $event->getResponse()
            ->setBody($this->uploadFile->execute($this->form->getValues()))
            ->setStatusCode(HttpStatus::CREATED);
    }

    /**
     * Throws exception if the size of post data is greater than post_max_size
     *
     * @throws DomainException
     */
    private function checkLastError()
    {
        $error = error_get_last();

        if (null !== $error) {
            throw new DomainException($error['message']);
        }
    }
}
