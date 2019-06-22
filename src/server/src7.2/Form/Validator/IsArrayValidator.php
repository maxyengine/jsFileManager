<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Abstraction\Filter;
use Nrg\Form\Abstraction\Validator;
use Nrg\Form\Element;
use Nrg\I18n\Value\CompositeMessage;

/**
 * Class TypeArray.
 */
class IsArrayValidator extends AbstractValidator
{
    public const CASE_NOT_ARRAY = 0;
    public const CASE_INVALID_VALUE = 1;

    /**
     * @var Filter[]
     */
    private $valueFilters = [];

    /**
     * @var Validator[]
     */
    private $valueValidators = [];

    /**
     * TypeArray constructor.
     */
    public function __construct()
    {
        $this
            ->adjustErrorText('please provide a valid array', self::CASE_NOT_ARRAY)
            ->adjustErrorText('the element with key \'%s\' is invalid', self::CASE_INVALID_VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (!is_array($element->getValue())) {
            $this->setErrorCase(self::CASE_NOT_ARRAY);

            return false;
        }

        $elementValue = $element->getValue();

        foreach ($elementValue as $key => $value) {
            $valueElement = new Element($key);

            foreach ($this->valueFilters as $valueFilter) {
                $valueElement->addFilter($valueFilter);
            }

            foreach ($this->valueValidators as $valueValidator) {
                $valueElement->addValidator($valueValidator);
            }

            $valueElement->setValue($value);

            if ($valueElement->hasError()) {
                $this->setErrorCase(self::CASE_INVALID_VALUE, $key);
                $this->setErrorMessage(new CompositeMessage(
                    $this->getErrorMessage(),
                    $valueElement->getErrorMessage()
                ));

                return false;
            }
            $elementValue[$key] = $valueElement->getValue();
        }
        $element->setValue($elementValue);

        return true;
    }

    /**
     * @param Filter $filter
     *
     * @return IsArrayValidator
     */
    public function addValueFilter(Filter $filter): self
    {
        $this->valueFilters[] = $filter;

        return $this;
    }

    /**
     * @param Validator $validator
     *
     * @return IsArrayValidator
     */
    public function addValueValidator(Validator $validator): self
    {
        $this->valueValidators[] = $validator;

        return $this;
    }
}
