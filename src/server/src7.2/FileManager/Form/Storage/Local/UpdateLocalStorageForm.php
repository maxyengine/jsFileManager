<?php

namespace Nrg\FileManager\Form\Storage\Local;

use Nrg\FileManager\Form\Storage\UpdateStorageForm;
use Nrg\FileManager\Form\Storage\Local\Element\RootElement;
use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class UpdateLocalStorageForm
 */
class UpdateLocalStorageForm extends UpdateStorageForm
{
    public function __construct(Translator $translator, IsUniqueName $isUniqueName)
    {
        parent::__construct($translator, $isUniqueName);

        $this->addElement(new RootElement());
    }
}