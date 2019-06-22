<?php

namespace Nrg\Data\Validator\Condition;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Condition\In as InCondition;
use Nrg\Form\Element;

/**
 * Class In.
 */
class In extends AbstractValidator
{
    public const CASE_PROPERTY_RANGE_IS_REQUIRED = 0;
    public const CASE_PROPERTY_RANGE_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_RANGE_MUST_BE_ARRAY = 2;

    public function __construct()
    {
        $this
            ->adjustErrorText('property \'range\' is required', self::CASE_PROPERTY_RANGE_IS_REQUIRED)
            ->adjustErrorText('property \'range\' do not be empty', self::CASE_PROPERTY_RANGE_NOT_BE_EMPTY)
            ->adjustErrorText('property \'range\' must be array', self::CASE_PROPERTY_RANGE_MUST_BE_ARRAY)
        ;
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        if (!$this->validateRangeProperty($element->getValue())) {
            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    protected function validateRangeProperty(array $properties): bool
    {
        if (!isset($properties[InCondition::LITERAL_PROPERTY_RANGE])) {
            $this->setErrorCase(self::CASE_PROPERTY_RANGE_IS_REQUIRED);

            return false;
        }

        if (!is_array($properties[InCondition::LITERAL_PROPERTY_RANGE])) {
            $this->setErrorCase(self::CASE_PROPERTY_RANGE_MUST_BE_ARRAY);

            return false;
        }

        if (empty($properties[InCondition::LITERAL_PROPERTY_RANGE])) {
            $this->setErrorCase(self::CASE_PROPERTY_RANGE_NOT_BE_EMPTY);

            return false;
        }

        return true;
    }
}
