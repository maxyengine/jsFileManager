<?php

namespace Nrg\FileManager\Form\Hyperlink;

use Nrg\FileManager\Form\File\Element\PathElement;
use Nrg\FileManager\Form\File\Validator\UniquePath;
use Nrg\FileManager\Form\Hyperlink\Element\UrlElement;
use Nrg\FileManager\UseCase\File\IsUniquePath;
use Nrg\Form\Form;
use Nrg\Form\Validator\IsRequiredValidator;
use Nrg\I18n\Abstraction\Translator;

class CreateHyperlinkForm extends Form
{
    public function __construct(Translator $translator, IsUniquePath $isUniquePath)
    {
        parent::__construct($translator);

        $this
            ->addElement(
                (new PathElement())
                    ->addValidator(new IsRequiredValidator())
                    ->addValidator(new UniquePath($isUniquePath))
            )
            ->addElement(
                (new UrlElement())
                    ->addValidator(new IsRequiredValidator())
            );
    }
}