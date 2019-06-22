<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class InArrayValidator.
 */
class InArrayValidator extends AbstractValidator
{
    public const CASE_NOT_IN_ARRAY = 0;

    /**
     * @var array
     */
    private $haystack = [];

    /**
     * @var bool
     */
    private $strict = false;

    public function __construct()
    {
        $this->adjustErrorText('value must be in: \'%s\'', self::CASE_NOT_IN_ARRAY);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!in_array($element->getValue(), $this->haystack, $this->strict)) {
            $this->setErrorCase(self::CASE_NOT_IN_ARRAY, implode(', ', $this->haystack));

            return false;
        }

        return true;
    }

    /**
     * @param array $haystack
     *
     * @return InArrayValidator
     */
    public function setHaystack(array $haystack): self
    {
        $this->haystack = $haystack;

        return $this;
    }

    /**
     * @param bool $strict
     *
     * @return InArrayValidator
     */
    public function setStrict(bool $strict): self
    {
        $this->strict = $strict;

        return $this;
    }
}
