<?php

namespace Nrg\FileManager\Form\Directory;

use Nrg\FileManager\Form\File\Element\PathElement;
use Nrg\FileManager\Form\File\Validator\UniquePath;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

class CreateDirectoryForm extends Form
{
    public function __construct(Translator $translator, UniquePath $uniquePath)
    {
        parent::__construct($translator);

        $this->addElement(
            (new PathElement())
                ->addValidator($uniquePath)
        );
    }
}