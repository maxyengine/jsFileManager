<?php

namespace Nrg\Data\Element;

use Nrg\Data\Validator\Filter as FilterValidator;
use Nrg\Form\Element;
use Nrg\Form\Validator\IsArrayValidator;

/**
 * Class Filter.
 */
class Filter extends Element
{
    private $filterValidator;

    /**
     * @param string $name
     */
    public function __construct(string $name = 'filter')
    {
        parent::__construct($name);

        $this->filterValidator = new FilterValidator();

        $this
            ->addValidator(new IsArrayValidator())
            ->addValidator($this->filterValidator);
    }

    public function setAllowedFields(array $allowedFields): Filter
    {
        $this->filterValidator->setAllowedFields($allowedFields);

        return $this;
    }
}
