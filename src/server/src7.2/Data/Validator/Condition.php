<?php

namespace Nrg\Data\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Dto\Filtering;
use Nrg\Data\Element\Filter;
use Nrg\Form\Element;

/**
 * Class Condition.
 */
class Condition extends AbstractValidator
{
    public const LITERAL_PROPERTY_NAME = 'name';
    public const LITERAL_PROPERTY_FIELD = 'field';

    public const CASE_PROPERTY_NAME_IS_REQUIRED = 0;
    public const CASE_PROPERTY_NAME_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_NAME_MUST_BE_STRING = 2;
    public const CASE_PROPERTY_FIELD_IS_REQUIRED = 3;
    public const CASE_PROPERTY_FIELD_NOT_BE_EMPTY = 4;
    public const CASE_PROPERTY_FIELD_MUST_BE_STRING = 5;
    public const CASE_INVALID_NAME_VALUE = 6;
    public const CASE_INVALID_FIELD_VALUE = 7;

    /**
     * @var array
     */
    private $conditionNames = [];

    /**
     * @var array
     */
    private $allowedFields = [];

    public function __construct()
    {
        $this->conditionNames = array_keys(Filtering::CONDITION_CLASS_MAP);

        $this
            ->adjustErrorText('property \'name\' is required', self::CASE_PROPERTY_NAME_IS_REQUIRED)
            ->adjustErrorText('property \'name\' do not be empty', self::CASE_PROPERTY_NAME_NOT_BE_EMPTY)
            ->adjustErrorText('property \'name\' must be string', self::CASE_PROPERTY_NAME_MUST_BE_STRING)
            ->adjustErrorText('invalid  property \'name\' was provided, allowed: %s', self::CASE_INVALID_NAME_VALUE)
            ->adjustErrorText('property \'field\' is required', self::CASE_PROPERTY_FIELD_IS_REQUIRED)
            ->adjustErrorText('property \'field\' do not be empty', self::CASE_PROPERTY_FIELD_NOT_BE_EMPTY)
            ->adjustErrorText('property \'field\' must be string', self::CASE_PROPERTY_FIELD_MUST_BE_STRING)
            ->adjustErrorText('invalid  property \'field\' was provided, allowed: %s', self::CASE_INVALID_FIELD_VALUE)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (Filtering::isFilter($element->getValue())) {
            $filterElement = (new Filter())
                ->setAllowedFields($this->allowedFields)
                ->setValue($element->getValue())
            ;

            if ($filterElement->hasError()) {
                $this->setErrorMessage($filterElement->getErrorMessage());

                return false;
            }
        } else {
            if (!$this->validateNameProperty($element->getValue())) {
                return false;
            }

            if (!$this->validateFieldProperty($element->getValue())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $allowedFields
     *
     * @return Condition
     */
    public function setAllowedFields(array $allowedFields): self
    {
        $this->allowedFields = $allowedFields;

        return $this;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateNameProperty(array $properties): bool
    {
        if (!isset($properties[self::LITERAL_PROPERTY_NAME])) {
            $this->setErrorCase(self::CASE_PROPERTY_NAME_IS_REQUIRED);

            return false;
        }

        if (empty($properties[self::LITERAL_PROPERTY_NAME])) {
            $this->setErrorCase(self::CASE_PROPERTY_NAME_NOT_BE_EMPTY);

            return false;
        }

        if (!is_string($properties[self::LITERAL_PROPERTY_NAME])) {
            $this->setErrorCase(self::CASE_PROPERTY_NAME_MUST_BE_STRING);

            return false;
        }

        if (!in_array($properties[Filtering::LITERAL_PROPERTY_NAME], $this->conditionNames)) {
            $this->setErrorCase(self::CASE_INVALID_NAME_VALUE, implode(', ', $this->conditionNames));

            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateFieldProperty(array $properties): bool
    {
        if (!isset($properties[self::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_IS_REQUIRED);

            return false;
        }

        if (empty($properties[self::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_NOT_BE_EMPTY);

            return false;
        }

        if (!is_string($properties[self::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_MUST_BE_STRING);

            return false;
        }

        if (!in_array($properties[self::LITERAL_PROPERTY_FIELD], $this->allowedFields)) {
            $this->setErrorCase(self::CASE_INVALID_FIELD_VALUE, implode(', ', $this->allowedFields));

            return false;
        }

        return true;
    }
}
