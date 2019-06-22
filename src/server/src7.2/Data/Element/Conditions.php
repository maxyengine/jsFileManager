<?php

namespace Nrg\Data\Element;

use Nrg\Data\Dto\Filtering;
use Nrg\Data\Validator\Condition;
use Nrg\Data\Validator\Conditions as ConditionsValidator;
use Nrg\Form\Element;
use Nrg\Form\Validator\IsArrayValidator;

/**
 * Class Conditions
 */
class Conditions extends Element
{
    private $conditionValidator;

    /**
     * @param string $name
     */
    public function __construct(string $name = Filtering::LITERAL_PROPERTY_CONDITIONS)
    {
        parent::__construct($name);

        $this->conditionValidator = new Condition();

        $this
            ->addValidator(
                (new IsArrayValidator())
                    ->addValueValidator(new IsArrayValidator())
                    ->addValueValidator($this->conditionValidator)
            )
            ->addValidator(new ConditionsValidator())
        ;
    }

    /**
     * @param array $allowedFields
     *
     * @return Conditions
     */
    public function setAllowedFields(array $allowedFields): Conditions
    {
        $this->conditionValidator->setAllowedFields($allowedFields);

        return $this;
    }
}
