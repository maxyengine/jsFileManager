<?php

namespace Nrg\Data\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Dto\OrderBy as OrderByDTO;
use Nrg\Form\Element;

/**
 * Class OrderBy.
 */
class OrderBy extends AbstractValidator
{
    private const CASE_PROPERTY_FIELD_IS_REQUIRED = 0;
    private const CASE_PROPERTY_FIELD_NOT_BE_EMPTY = 1;
    private const CASE_PROPERTY_FIELD_MUST_BE_STRING = 2;
    private const CASE_PROPERTY_DIRECTION_NOT_BE_EMPTY = 3;
    private const CASE_PROPERTY_DIRECTION_MUST_BE_STRING = 4;
    private const CASE_INVALID_FIELD_VALUE = 5;
    private const CASE_INVALID_DIRECTION_VALUE = 6;

    private const DIRECTION_NAME_ENUM = [
        OrderByDTO::ASC_DIRECTION_NAME,
        OrderByDTO::DESC_DIRECTION_NAME,
    ];

    /**
     * @var array
     */
    private $allowedFields = [];

    public function __construct()
    {
        $this
            ->adjustErrorText('property \'field\' is required', self::CASE_PROPERTY_FIELD_IS_REQUIRED)
            ->adjustErrorText('property \'field\' do not be empty', self::CASE_PROPERTY_FIELD_NOT_BE_EMPTY)
            ->adjustErrorText('property \'field\' must be string', self::CASE_PROPERTY_FIELD_MUST_BE_STRING)
            ->adjustErrorText('invalid  property \'field\' was provided, allowed: %s', self::CASE_INVALID_FIELD_VALUE)
            ->adjustErrorText('property \'direction\' do not be empty', self::CASE_PROPERTY_DIRECTION_NOT_BE_EMPTY)
            ->adjustErrorText('property \'direction\' must be string', self::CASE_PROPERTY_DIRECTION_MUST_BE_STRING)
            ->adjustErrorText('invalid  property \'direction\' was provided, allowed: %s', self::CASE_INVALID_DIRECTION_VALUE)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!$this->validateFieldProperty($element->getValue())) {
            return false;
        }

        if (!$this->validateDirectionProperty($element->getValue())) {
            return false;
        }

        return true;
    }

    /**
     * @param array $allowedFields
     *
     * @return OrderBy
     */
    public function setAllowedFields(array $allowedFields): OrderBy
    {
        $this->allowedFields = $allowedFields;

        return $this;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateFieldProperty(array $properties): bool
    {
        if (!isset($properties[OrderByDTO::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_IS_REQUIRED);

            return false;
        }

        if (empty($properties[OrderByDTO::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_NOT_BE_EMPTY);

            return false;
        }

        if (!is_string($properties[OrderByDTO::LITERAL_PROPERTY_FIELD])) {
            $this->setErrorCase(self::CASE_PROPERTY_FIELD_MUST_BE_STRING);

            return false;
        }

        if (!in_array($properties[OrderByDTO::LITERAL_PROPERTY_FIELD], $this->allowedFields)) {
            $this->setErrorCase(self::CASE_INVALID_FIELD_VALUE, implode(', ', $this->allowedFields));

            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateDirectionProperty(array $properties): bool
    {
        if (isset($properties[OrderByDTO::LITERAL_PROPERTY_DIRECTION])) {

            if (empty($properties[OrderByDTO::LITERAL_PROPERTY_DIRECTION])) {
                $this->setErrorCase(self::CASE_PROPERTY_DIRECTION_NOT_BE_EMPTY);

                return false;
            }

            if (!is_string($properties[OrderByDTO::LITERAL_PROPERTY_DIRECTION])) {
                $this->setErrorCase(self::CASE_PROPERTY_DIRECTION_MUST_BE_STRING);

                return false;
            }

            if (!in_array($properties[OrderByDTO::LITERAL_PROPERTY_DIRECTION], self::DIRECTION_NAME_ENUM)) {
                $this->setErrorCase(self::CASE_INVALID_DIRECTION_VALUE, implode(', ', self::DIRECTION_NAME_ENUM));

                return false;
            }
        }

        return true;
    }
}
