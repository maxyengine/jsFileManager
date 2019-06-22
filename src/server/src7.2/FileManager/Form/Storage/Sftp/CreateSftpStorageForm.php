<?php

namespace Nrg\FileManager\Form\Storage\Sftp;

use Nrg\FileManager\Form\Storage\CreateStorageForm;
use Nrg\FileManager\Form\Storage\Ftp\Element\HostElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\PasswordElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\PortElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\RootElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\TimeoutElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\UsernameElement;
use Nrg\FileManager\Form\Storage\Sftp\Element\PrivateKeyElement;
use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\Form\Validator\IsRequiredValidator;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class CreateSftpStorageForm
 */
class CreateSftpStorageForm extends CreateStorageForm
{
    public function __construct(Translator $translator, IsUniqueName $isUniqueName)
    {
        parent::__construct($translator, $isUniqueName);

        $this
            ->addElement((new HostElement())->addValidator(new IsRequiredValidator()))
            ->addElement((new UsernameElement())->addValidator(new IsRequiredValidator()))
            ->addElement(new PasswordElement())
            ->addElement(new PrivateKeyElement())
            ->addElement(new PortElement())
            ->addElement(new RootElement())
            ->addElement(new TimeoutElement());
    }
}