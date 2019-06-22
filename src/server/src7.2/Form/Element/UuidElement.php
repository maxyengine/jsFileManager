<?php

namespace Nrg\Form\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\IsRequiredValidator;
use Nrg\Form\Validator\IsUuidValidator;

/**
 * Class UuidElement
 */
class UuidElement extends Element
{
    public function __construct(string $name = 'id')
    {
        parent::__construct($name);

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsRequiredValidator())
            ->addValidator(new IsUuidValidator());
    }
}
