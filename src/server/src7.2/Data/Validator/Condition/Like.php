<?php

namespace Nrg\Data\Validator\Condition;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Condition\Like as LikeCondition;
use Nrg\Form\Element;

/**
 * Class Like.
 */
class Like extends AbstractValidator
{
    public const CASE_PROPERTY_VALUE_IS_REQUIRED = 0;
    public const CASE_PROPERTY_VALUE_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_VALUE_MUST_BE_STRING = 2;
    public const CASE_PROPERTY_TYPE_OPERAND_NOT_BE_EMPTY = 3;
    public const CASE_PROPERTY_TYPE_OPERAND_MUST_BE_INTEGER = 4;
    public const CASE_PROPERTY_CASE_INSENSITIVITY_NOT_BE_EMPTY = 5;
    public const CASE_PROPERTY_CASE_INSENSITIVITY_MUST_BE_BOOLEAN = 6;


    public function __construct()
    {
        $this
            ->adjustErrorText('property \'value\' is required', self::CASE_PROPERTY_VALUE_IS_REQUIRED)
            ->adjustErrorText('property \'value\' do not be empty', self::CASE_PROPERTY_VALUE_NOT_BE_EMPTY)
            ->adjustErrorText('property \'value\' must be string', self::CASE_PROPERTY_VALUE_MUST_BE_STRING)
            ->adjustErrorText('property \'typeOperand\' do not be empty', self::CASE_PROPERTY_TYPE_OPERAND_NOT_BE_EMPTY)
            ->adjustErrorText('property \'typeOperand\' must be integer', self::CASE_PROPERTY_TYPE_OPERAND_MUST_BE_INTEGER)
            ->adjustErrorText('property \'forceCaseInsensitivity\' do not be empty', self::CASE_PROPERTY_CASE_INSENSITIVITY_NOT_BE_EMPTY)
            ->adjustErrorText('property \'forceCaseInsensitivity\' must be boolean', self::CASE_PROPERTY_CASE_INSENSITIVITY_MUST_BE_BOOLEAN)
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

        if (!$this->validateTypeOperandProperty($element->getValue())) {
            return false;
        }

        if (!$this->validateCaseInsensitivityProperty($element->getValue())) {
            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateValueProperty(array $properties): bool
    {
        if (!isset($properties[LikeCondition::LITERAL_PROPERTY_VALUE])) {
            $this->setErrorCase(self::CASE_PROPERTY_VALUE_IS_REQUIRED);

            return false;
        }

        if (empty($properties[LikeCondition::LITERAL_PROPERTY_VALUE])) {
            $this->setErrorCase(self::CASE_PROPERTY_VALUE_NOT_BE_EMPTY);

            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateTypeOperandProperty(array $properties): bool
    {
        if (isset($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
            if (empty($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
                $this->setErrorCase(self::CASE_PROPERTY_TYPE_OPERAND_NOT_BE_EMPTY);

                return false;
            }

            if (!is_int($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
                $this->setErrorCase(self::CASE_PROPERTY_TYPE_OPERAND_MUST_BE_INTEGER);

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
    private function validateCaseInsensitivityProperty(array $properties): bool
    {
        if (isset($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
            if (empty($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
                $this->setErrorCase(self::CASE_PROPERTY_TYPE_OPERAND_NOT_BE_EMPTY);

                return false;
            }

            if (!is_int($properties[LikeCondition::LITERAL_PROPERTY_TYPE_OPERAND])) {
                $this->setErrorCase(self::CASE_PROPERTY_TYPE_OPERAND_MUST_BE_INTEGER);

                return false;
            }
        }

        return true;
    }
}
