<?php

namespace Nrg\Data\Validator\Condition;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class ScalarValueHandler.
 */
class ScalarValueHandler extends AbstractValidator
{
    public const CASE_PROPERTY_VALUE_IS_REQUIRED = 0;
    public const CASE_PROPERTY_VALUE_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_VALUE_MUST_BE_SCALAR = 2;
    private const LITERAL_PROPERTY_VALUE = 'value';

    public function __construct()
    {
        $this
            ->adjustErrorText('property \'value\' is required', self::CASE_PROPERTY_VALUE_IS_REQUIRED)
            ->adjustErrorText('property \'value\' do not be empty', self::CASE_PROPERTY_VALUE_NOT_BE_EMPTY)
            ->adjustErrorText('property \'value\' must be scalar', self::CASE_PROPERTY_VALUE_MUST_BE_SCALAR)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!$this->validateValueProperty($element->getValue())) {
            return false;
        }

        return true;
    }

    private function validateValueProperty(array $properties)
    {
        if (!isset($properties[self::LITERAL_PROPERTY_VALUE])) {
            $this->setErrorCase(self::CASE_PROPERTY_VALUE_IS_REQUIRED);

            return false;
        }

        if (empty($properties[self::LITERAL_PROPERTY_VALUE])) {
            $this->setErrorCase(self::CASE_PROPERTY_VALUE_NOT_BE_EMPTY);

            return false;
        }

        if (!is_scalar($properties[self::LITERAL_PROPERTY_VALUE])) {
            $this->setErrorCase(self::CASE_PROPERTY_VALUE_MUST_BE_SCALAR);

            return false;
        }

        return true;
    }
}
