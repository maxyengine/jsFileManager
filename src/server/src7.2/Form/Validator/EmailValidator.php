<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class EmailValidator.
 */
class EmailValidator extends AbstractValidator
{
    public function __construct()
    {
        $this->adjustErrorText('please provide a valid e-mail');
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return filter_var($element->getValue(), FILTER_VALIDATE_EMAIL);
    }
}
