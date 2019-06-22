<?php

namespace Nrg\FileManager\Form\File\Element;

use Nrg\Form\Element;
use Nrg\Form\Validator\IsBooleanValidator;

/**
 * Class OverwriteElement
 */
class OverwriteElement extends Element
{
    public function __construct(string $name = 'overwrite')
    {
        parent::__construct($name);

        $this->addValidator(new IsBooleanValidator());
    }
}