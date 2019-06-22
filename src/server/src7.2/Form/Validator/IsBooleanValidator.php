<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class BooleanValidator
 */
class IsBooleanValidator extends AbstractValidator
{
    public function __construct()
    {
        $this->adjustErrorText('please provide a valid boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return is_bool($element->getValue());
    }
}
