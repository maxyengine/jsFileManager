<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

class IsUuidValidator extends AbstractValidator
{
    private const PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    public function __construct()
    {
        $this->adjustErrorText('please provide a valid uuid');
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        return 1 === preg_match(self::PATTERN, $element->getValue());
    }
}
