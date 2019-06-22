<?php

namespace Nrg\FileManager\Form\Storage\Local;

use Nrg\FileManager\Form\Storage\CreateStorageForm;
use Nrg\FileManager\Form\Storage\Local\Element\RootElement;
use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\Form\Validator\IsRequiredValidator;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class CreateLocalStorageForm
 */
class CreateLocalStorageForm extends CreateStorageForm
{
    public function __construct(Translator $translator, IsUniqueName $isUniqueName)
    {
        parent::__construct($translator, $isUniqueName);

        $this->addElement((new RootElement())->addValidator(new IsRequiredValidator()));
    }
}