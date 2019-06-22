<?php

namespace Nrg\FileManager\Form\Storage\Validator;

use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

class UniqueNameValidator extends AbstractValidator
{
    public const CASE_ALREADY_EXISTS = 0;

    /**
     * @var IsUniqueName
     */
    private $isUniqueName;

    /**
     * @param IsUniqueName $isUniqueName
     */
    public function __construct(IsUniqueName $isUniqueName)
    {
        $this->adjustErrorText('storage with name \'%s\' already exists', self::CASE_ALREADY_EXISTS);
        $this->isUniqueName = $isUniqueName;
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        $this->setErrorCase(self::CASE_ALREADY_EXISTS, $element->getValue());

        if ($element->getForm()->hasElement('id')) {
            return $this->isUniqueName->execute(
                $element->getValue(),
                $element->getForm()->getElement('id')->getValue()
            );
        }

        return $this->isUniqueName->execute($element->getValue());
    }
}