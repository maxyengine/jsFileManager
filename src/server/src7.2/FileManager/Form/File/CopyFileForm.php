<?php

namespace Nrg\FileManager\Form\File;

use Nrg\FileManager\Form\File\Element\OverwriteElement;
use Nrg\FileManager\Form\File\Element\PathElement;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class CopyFileForm
 */
class CopyFileForm extends Form
{
    public function __construct(Translator $translator)
    {
        parent::__construct($translator);

        $this
            ->addElement(new PathElement())
            ->addElement(new PathElement('newPath'))
            ->addElement(new OverwriteElement());
    }
}
