<?php

namespace Nrg\FileManager\Action\Directory;

use Nrg\FileManager\Form\Directory\CreateDirectoryForm;
use Nrg\Http\Exception\ValidationException;
use Nrg\Http\Value\HttpStatus;
use Nrg\FileManager\UseCase\Directory\CreateDirectory;
use Nrg\Http\Event\HttpExchangeEvent;
use ReflectionException;

/**
 * Class CreateDirectoryAction.
 *
 * Creates a directory in a directory with a parentPath.
 */
class CreateDirectoryAction
{
    /**
     * @var CreateDirectory
     */
    private $createDirectory;

    /**
     * @var CreateDirectoryForm
     */
    private $form;

    /**
     * CreateDirectory constructor.
     *
     * @param CreateDirectory $createDirectory
     */
    public function __construct(CreateDirectoryForm $form, CreateDirectory $createDirectory)
    {
        $this->form = $form;
        $this->createDirectory = $createDirectory;
    }

    /**
     * Creates a directory with a path.
     *
     * @param HttpExchangeEvent $event
     *
     * @throws ValidationException
     * @throws ReflectionException
     */
    public function onNext(HttpExchangeEvent $event): void
    {
        $this->form->populate($event->getRequest()->getBodyParams());

        if ($this->form->hasErrors()) {
            throw new ValidationException($this->form->getErrors());
        }

        $event->getResponse()
            ->setStatusCode(HttpStatus::CREATED)
            ->setBody($this->createDirectory->execute($this->form->getValues()));
    }
}
