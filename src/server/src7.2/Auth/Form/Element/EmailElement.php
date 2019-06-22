<?php

namespace Nrg\Auth\Form\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\EmailValidator;
use Nrg\Form\Validator\LengthValidator;
use Nrg\Form\Validator\IsStringValidator;
use Nrg\Form\Validator\IsRequiredValidator;

/**
 * Class EmailElement
 */
class EmailElement extends Element
{
    public function __construct()
    {
        parent::__construct('email');
        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsRequiredValidator())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMin(1)
                    ->setMax(255)
            )
            ->addValidator(new EmailValidator());
    }
}
