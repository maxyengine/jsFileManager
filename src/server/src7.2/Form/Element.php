<?php

namespace Nrg\Form;

use Nrg\Form\Abstraction\Filter;
use Nrg\Form\Abstraction\Validator;
use Nrg\Form\Validator\IsRequiredValidator;

/**
 * Class Element.
 */
class Element
{
    use ErrorAbility;

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $hasValue = false;

    /**
     * @var Filter[]
     */
    private $filters = [];

    /**
     * @var Validator[]
     */
    private $validators = [];

    /**
     * @var bool
     */
    private $hasErrorsCache;

    /**
     * @var Form
     */
    private $form;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Element
     */
    public function setValue($value): Element
    {
        $this->value = $value;
        $this->hasValue = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->hasValue;
    }

    /**
     * @return bool
     */
    public function hasEmptyValue(): bool
    {
        return empty($this->getValue());
    }

    /**
     * @param Filter $filter
     *
     * @return Element
     */
    public function addFilter(Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param Validator $validator
     *
     * @return Element
     */
    public function addValidator(Validator $validator): self
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @return Element
     */
    public function filter(): self
    {
        foreach ($this->filters as $filter) {
            $filter->apply($this);
        }

        return $this;
    }

    /**
     * @param bool $useCache
     *
     * @return bool
     */
    public function hasError($useCache = true): bool
    {
        if ($useCache && null !== $this->hasErrorsCache) {
            return $this->hasErrorsCache;
        }

        $this->filter();

        foreach ($this->validators as $validator) {
            if (
                !$validator instanceof IsRequiredValidator &&
                (!$this->hasValue() || $this->hasEmptyValue())
            ) {
                continue;
            }

            if (!$validator->isValid($this)) {
                $this->hasErrorsCache = true;
                $this->setErrorMessage($validator->getErrorMessage());

                return true;
            }
        }
        $this->hasErrorsCache = false;

        return false;
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }
}
