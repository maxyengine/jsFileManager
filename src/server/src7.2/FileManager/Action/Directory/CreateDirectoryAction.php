<?php

namespace Nrg\FileManager\Action\Directory;

use Nrg\FileManager\Form\Directory\CreateDirectoryForm;
use Nrg\Http\Value\HttpStatus;
use Nrg\FileManager\UseCase\Directory\CreateDirectory;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Rx\Abstraction\Observer;
use Nrg\Rx\Service\ObserverStub;

/**
 * Class CreateDirectoryAction.
 *
 * Creates a directory in a directory with a parentPath.
 */
class CreateDirectoryAction implements Observer
{
    use ObserverStub;

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
     */
    public function onNext($event)
    {
        $this->form->populate($event->getRequest()->getBodyParams());

        if ($this->form->hasErrors()) {
            $event->getResponse()
                ->setStatus(new HttpStatus(HttpStatus::UNPROCESSABLE_ENTITY))
                ->setBody($this->form->serialize());
        } else {
            $event->getResponse()
                ->setStatus(new HttpStatus(HttpStatus::CREATED))
                ->setBody($this->createDirectory->execute($this->form->serialize()));
        }
    }

    public function onError($throwable, $event)
    {
        var_dump($throwable); exit;
    }
}
