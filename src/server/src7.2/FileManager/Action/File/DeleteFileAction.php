<?php

namespace Nrg\FileManager\Action\File;

use Exception;
use Nrg\FileManager\UseCase\File\DeleteFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;

/**
 * Class DeleteFileAction.
 *
 * Deletes a file.
 */
class DeleteFileAction
{
    /**
     * @var DeleteFileAction
     */
    private $deleteFile;

    /**
     * @param DeleteFile $deleteFile
     */
    public function __construct(DeleteFile $deleteFile)
    {
        $this->deleteFile = $deleteFile;
    }

    /**
     * Copies a file.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws Exception
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $params = $event->getRequest()->getBodyParams();

        $this->deleteFile->execute($params);

        $event->getResponse()->setStatusCode(HttpStatus::NO_CONTENT);
    }
}
