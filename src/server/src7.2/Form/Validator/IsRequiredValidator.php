<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class IsRequiredValidator
 */
class IsRequiredValidator extends AbstractValidator
{
    private const CASE_NOT_FILLED = 0;

    public function __construct()
    {
        $this->adjustErrorText('please fill out this field', self::CASE_NOT_FILLED);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return $element->hasValue() && !$element->hasEmptyValue();
    }
}
