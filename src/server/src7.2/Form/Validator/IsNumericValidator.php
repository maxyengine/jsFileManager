<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

class IsNumericValidator extends AbstractValidator
{
    /**
     * TypeString constructor.
     */
    public function __construct()
    {
        $this->adjustErrorText('please provide a valid number');
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return is_numeric($element->getValue());
    }
}
