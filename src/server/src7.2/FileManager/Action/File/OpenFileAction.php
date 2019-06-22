<?php

namespace Nrg\FileManager\Action\File;

use Exception;
use Nrg\FileManager\Entity\File;
use Nrg\FileManager\Entity\Hyperlink;
use Nrg\FileManager\UseCase\File\ReadFile;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Value\HttpStatus;

/**
 * Class OpenFileAction.
 *
 * Opens a file by a path.
 */
class OpenFileAction
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var ReadFile
     */
    private $readFile;

    /**
     * @param ReadFile $readFile
     */
    public function __construct(ReadFile $readFile)
    {
        $this->readFile = $readFile;
    }

    /**
     * Opens a file by a path.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws Exception
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $params = $event->getRequest()->getQueryParams();

        $this->file = $this->readFile->execute($params);

        if ($this->file instanceof Hyperlink) {
            $event->getResponse()
                ->setStatusCode(HttpStatus::FOUND)
                ->setHeader('Location', (string)$this->file->getUrl());
        } else {
            $event->getResponse()
                ->setHeader('Content-Type', $this->file->getMimeType().'; charset=utf-8')
                ->setHeader('Content-Disposition', 'filename="'.$this->file->getPath()->getFileName().'"');


        }
    }

    public function onComplete()
    {
        if ($this->file instanceof Hyperlink) {
            return;
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        echo $this->file->getContents();
    }
}
