<?php

namespace Nrg\Data\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Dto\Filter as FilterDTO;
use Nrg\Data\Dto\Filtering;
use Nrg\Data\Element\Conditions;
use Nrg\Form\Element;
use Nrg\I18n\Value\CompositeMessage;

/**
 * Class Filter.
 */
class Filter extends AbstractValidator
{
    public const CASE_PROPERTY_UNION_IS_REQUIRED = 0;
    public const CASE_PROPERTY_UNION_NOT_BE_EMPTY = 1;
    public const CASE_PROPERTY_UNION_MUST_BE_STRING = 2;
    public const CASE_PROPERTY_CONDITIONS_IS_REQUIRED = 3;
    public const CASE_PROPERTY_CONDITIONS_NOT_BE_EMPTY = 4;
    public const CASE_PROPERTY_CONDITIONS_MUST_BE_ARRAY = 5;
    public const CASE_INVALID_VALUE_UNION = 6;
    public const CASE_INVALID_CONDITIONS = 7;

    private const PROPERTY_UNION_ENUM = [
        FilterDTO::UNION_AND,
        FilterDTO::UNION_OR,
    ];

    /**
     * @var array
     */
    private $allowedFields = [];

    public function __construct()
    {
        $this
            ->adjustErrorText('property \'union\' is required', self::CASE_PROPERTY_UNION_IS_REQUIRED)
            ->adjustErrorText('property \'union\' do not be empty', self::CASE_PROPERTY_UNION_NOT_BE_EMPTY)
            ->adjustErrorText('property \'union\' must be string', self::CASE_PROPERTY_UNION_MUST_BE_STRING)
            ->adjustErrorText('invalid  property \'union\' was provided, allowed: %s', self::CASE_INVALID_VALUE_UNION)
            ->adjustErrorText('property \'conditions\' is required', self::CASE_PROPERTY_CONDITIONS_IS_REQUIRED)
            ->adjustErrorText('property \'conditions\' do not be empty', self::CASE_PROPERTY_CONDITIONS_NOT_BE_EMPTY)
            ->adjustErrorText('property \'conditions\' must be array', self::CASE_PROPERTY_CONDITIONS_MUST_BE_ARRAY)
            ->adjustErrorText('property \'conditions\' invalid', self::CASE_INVALID_CONDITIONS)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!$this->validateUnionProperty($element->getValue())) {
            return false;
        }

        if (!$this->validateConditionsProperty($element->getValue())) {
            return false;
        }

        return true;
    }

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
    private function validateUnionProperty(array $properties): bool
    {
        if (!isset($properties[Filtering::LITERAL_PROPERTY_UNION])) {
            $this->setErrorCase(self::CASE_PROPERTY_UNION_IS_REQUIRED);

            return false;
        }

        if (empty($properties[Filtering::LITERAL_PROPERTY_UNION])) {
            $this->setErrorCase(self::CASE_PROPERTY_UNION_NOT_BE_EMPTY);

            return false;
        }

        if (!in_array($properties[Filtering::LITERAL_PROPERTY_UNION], self::PROPERTY_UNION_ENUM)) {
            $this->setErrorCase(self::CASE_INVALID_VALUE_UNION, implode(', ', self::PROPERTY_UNION_ENUM));

            return false;
        }

        return true;
    }

    /**
     * @param array $properties
     *
     * @return bool
     */
    private function validateConditionsProperty(array $properties): bool
    {
        if (!isset($properties[Filtering::LITERAL_PROPERTY_CONDITIONS])) {
            $this->setErrorCase(self::CASE_PROPERTY_CONDITIONS_IS_REQUIRED);

            return false;
        }

        if (!is_array($properties[Filtering::LITERAL_PROPERTY_CONDITIONS])) {
            $this->setErrorCase(self::CASE_PROPERTY_CONDITIONS_MUST_BE_ARRAY);

            return false;
        }

        if (empty($properties[Filtering::LITERAL_PROPERTY_CONDITIONS])) {
            $this->setErrorCase(self::CASE_PROPERTY_CONDITIONS_NOT_BE_EMPTY);

            return false;
        }

        $conditions = (new Conditions())
            ->setAllowedFields($this->allowedFields)
            ->setValue($properties[Filtering::LITERAL_PROPERTY_CONDITIONS])
        ;

        if ($conditions->hasError()) {
            $this->setErrorCase(self::CASE_INVALID_CONDITIONS);
            $this->setErrorMessage(
                new CompositeMessage(
                    $this->getErrorMessage(),
                    $conditions->getErrorMessage()
                )
            );

            return false;
        }

        return true;
    }
}
