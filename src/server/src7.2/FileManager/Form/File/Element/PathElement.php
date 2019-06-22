<?php

namespace Nrg\FileManager\Form\File\Element;

use Nrg\FileManager\Form\File\Validator\PathValidator;
use Nrg\Form\Element;
use Nrg\Form\Validator\IsStringValidator;

class PathElement extends Element
{
    public function __construct(string $name = 'path')
    {
        parent::__construct($name);

        $this
            ->addValidator(new IsStringValidator())
            ->addValidator(new PathValidator());
    }
}