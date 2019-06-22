<?php

namespace Nrg\Data\Element;

use Nrg\Data\Validator\OrderBy as OrderByValidator;
use Nrg\Form\Element;
use Nrg\Form\Validator\IsArrayValidator;

/**
 * Class OrderBy.
 */
class OrderBy extends Element
{
    private $orderByValidator;

    /**
     * @param string $name
     */
    public function __construct(string $name = 'orderBy')
    {
        parent::__construct($name);

        $this->orderByValidator = new OrderByValidator();

        $this
            ->addValidator(
                (new IsArrayValidator())
                    ->addValueValidator(new IsArrayValidator())
                    ->addValueValidator($this->orderByValidator)
            );
    }

    public function setAllowedFields(array $allowedFields): OrderBy
    {
        $this->orderByValidator->setAllowedFields($allowedFields);

        return $this;
    }
}
