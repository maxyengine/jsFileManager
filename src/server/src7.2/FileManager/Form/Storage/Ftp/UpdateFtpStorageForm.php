<?php

namespace Nrg\FileManager\Form\Storage\Ftp;

use Nrg\FileManager\Form\Storage\UpdateStorageForm;
use Nrg\FileManager\Form\Storage\Ftp\Element\HostElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\PassiveElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\PasswordElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\PortElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\RootElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\SslElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\TimeoutElement;
use Nrg\FileManager\Form\Storage\Ftp\Element\UsernameElement;
use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class UpdateFtpStorageForm
 */
class UpdateFtpStorageForm extends UpdateStorageForm
{
    public function __construct(Translator $translator, IsUniqueName $isUniqueName)
    {
        parent::__construct($translator, $isUniqueName);

        $this
            ->addElement(new HostElement())
            ->addElement(new UsernameElement())
            ->addElement(new PasswordElement())
            ->addElement(new PortElement())
            ->addElement(new RootElement())
            ->addElement(new PassiveElement())
            ->addElement(new SslElement())
            ->addElement(new TimeoutElement());
    }
}
