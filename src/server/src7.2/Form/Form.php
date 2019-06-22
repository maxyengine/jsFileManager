<?php

namespace Nrg\Form;

use Nrg\Form\I18n\FormDictionary;
use Nrg\I18n\Abstraction\Translator;
use Nrg\I18n\Service\TranslatorAbility;
use ReflectionException;

/**
 * Class Form.
 */
class Form
{
    use TranslatorAbility;

    /**
     * @var Element[]
     */
    public $elements = [];

    /**
     * @var bool
     */
    private $hasErrorsCache;

    /**
     * @param null|Translator $translator
     */
    public function __construct(Translator $translator = null)
    {
        $this->translator = $translator;
        $this->addDictionary(FormDictionary::class);
    }

    /**
     * @param Element $element
     *
     * @return Form
     */
    public function addElement(Element $element): self
    {
        $this->elements[$element->getName()] = $element;
        $element->setForm($this);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Element
     */
    public function getElement(string $name): Element
    {
        return $this->elements[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasElement(string $name): bool
    {
        return isset($this->elements[$name]);
    }

    /**
     * @param array $data
     *
     * @return Form
     */
    public function populate(array $data): self
    {
        foreach ($data as $name => $item) {
            if (isset($this->elements[$name])) {
                $this->elements[$name]->setValue($data[$name]);
            }
        }

        return $this;
    }

    /**
     * @param bool $useCache
     *
     * @return bool
     */
    public function hasErrors($useCache = true): bool
    {
        if ($useCache && null !== $this->hasErrorsCache) {
            return $this->hasErrorsCache;
        }

        $this->hasErrorsCache = false;
        foreach ($this->elements as $element) {
            if ($element->hasError() && !$this->hasErrorsCache) {
                $this->hasErrorsCache = true;
            }
        }

        return $this->hasErrorsCache;
    }

    /**
     * @param bool $useCache
     *
     * @return array
     *
     * @throws ReflectionException
     */
    public function getErrors($useCache = true): array
    {
        $errors = [];
        foreach ($this->elements as $element) {
            if ($element->hasError($useCache)) {
                $errors[$element->getName()] = $this->t($element->getErrorMessage());
            }
        }

        return $errors;
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->elements as $element) {
            if ($element->hasValue()) {
                $values[$element->getName()] = $element->getValue();
            }
        }

        return $values;
    }
}
