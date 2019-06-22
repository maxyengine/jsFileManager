<?php

namespace Nrg\FileManager\Form\Storage\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\LengthValidator;
use Nrg\Form\Validator\IsStringValidator;

class DescriptionElement extends Element
{
    public function __construct()
    {
        parent::__construct('description');

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMin(1)
                    ->setMax(255)
            );
    }
}