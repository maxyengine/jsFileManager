<?php

namespace Nrg\FileManager\Form\File\Validator;

use Nrg\FileManager\Value\Path;
use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

class PathValidator extends AbstractValidator
{
    public function __construct()
    {
        $this->adjustErrorText('Invalid path was provided');
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        return Path::isValid($element->getValue());
    }
}