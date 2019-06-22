<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class IsUrlValidator
 */
class IsUrlValidator extends AbstractValidator
{
    public function __construct()
    {
        $this->adjustErrorText('please provide a valid url');
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return filter_var($element->getValue(), FILTER_VALIDATE_URL);
    }
}
