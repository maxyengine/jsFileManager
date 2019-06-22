<?php

namespace Nrg\FileManager\Form\Directory;

use Nrg\FileManager\Form\File\Element\PathElement;
use Nrg\FileManager\Form\File\Validator\UniquePath;
use Nrg\FileManager\UseCase\File\IsUniquePath;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

class CreateDirectoryForm extends Form
{
    public function __construct(Translator $translator, IsUniquePath $isUniqueName)
    {
        parent::__construct($translator);

        $this->addElement(
            (new PathElement())
                ->addValidator(new UniquePath($isUniqueName))
        );
    }
}