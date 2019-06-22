<?php

namespace Nrg\Data\Validator\Condition;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Condition\Range as RangeCondition;
use Nrg\Form\Element;

/**
 * Class Range.
 */
class Range extends AbstractValidator
{
    public const CASE_PROPERTIES_MIN_OR_MAX_IS_REQUIRED = 0;
    public const CASE_PROPERTY_MIN_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_MIN_MUST_BE_SCALAR = 2;
    public const CASE_PROPERTY_MAX_NOT_BE_EMPTY = 3;
    public const CASE_PROPERTY_MAX_MUST_BE_SCALAR = 4;
    public const CASE_PROPERTY_MIN_GREATER_PROPERTY_MAX = 5;

    public function __construct()
    {
        $this
            ->adjustErrorText('property \'min\' or \'max\' is required', self::CASE_PROPERTIES_MIN_OR_MAX_IS_REQUIRED)
            ->adjustErrorText('property \'min\' do not be empty', self::CASE_PROPERTY_MIN_NOT_BE_EMPTY)
            ->adjustErrorText('property \'min\' must be scalar', self::CASE_PROPERTY_MIN_MUST_BE_SCALAR)
            ->adjustErrorText('property \'max\' do not be empty', self::CASE_PROPERTY_MAX_NOT_BE_EMPTY)
            ->adjustErrorText('property \'max\' must be scalar', self::CASE_PROPERTY_MAX_MUST_BE_SCALAR)
            ->adjustErrorText(
                'property \'min\' do not can be greater \'max\'',
                self::CASE_PROPERTY_MIN_GREATER_PROPERTY_MAX
            );
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!$this->isExistMinAndMaxProperty($element->getValue())) {
            return false;
        }

        if (!$this->validateMinProperty($element->getValue())) {
            return false;
        }

        if (!$this->validateMaxProperty($element->getValue())) {
            return false;
        }

        if ($this->minGreaterThanMax($element->getValue())) {
            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    protected function validateMinProperty(array $properties): bool
    {
        if (isset($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
            if (empty($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_NOT_BE_EMPTY);

                return false;
            }

            if (!is_scalar($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_MUST_BE_SCALAR);

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    protected function validateMaxProperty(array $properties): bool
    {
        if (isset($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
            if (empty($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
                $this->setErrorCase(self::CASE_PROPERTY_MAX_NOT_BE_EMPTY);

                return false;
            }

            if (!is_scalar($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
                $this->setErrorCase(self::CASE_PROPERTY_MAX_MUST_BE_SCALAR);

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function isExistMinAndMaxProperty(array $properties): bool
    {
        if (!isset($properties[RangeCondition::LITERAL_PROPERTY_MIN], $properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
            $this->setErrorCase(self::CASE_PROPERTIES_MIN_OR_MAX_IS_REQUIRED);

            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function minGreaterThanMax(array $properties): bool
    {
        if ($this->isExistMinAndMaxProperty($properties)) {
            if ($properties[RangeCondition::LITERAL_PROPERTY_MIN] > $properties[RangeCondition::LITERAL_PROPERTY_MAX]) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_GREATER_PROPERTY_MAX);

                return true;
            }
        }

        return false;
    }
}
