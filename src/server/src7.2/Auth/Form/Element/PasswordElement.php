<?php

namespace Nrg\Auth\Form\Element;

use Nrg\Auth\Form\Validator\ValidEmailAndPassword;
use Nrg\Auth\UseCase\AreValidEmailAndPassword;
use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\LengthValidator;
use Nrg\Form\Validator\IsStringValidator;
use Nrg\Form\Validator\IsRequiredValidator;

/**
 * Class PasswordElement.
 */
class PasswordElement extends Element
{
    public function __construct(AreValidEmailAndPassword $areValidEmailAndPassword)
    {
        parent::__construct('password');

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsRequiredValidator())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMin(1)
                    ->setMax(70)
            )
            ->addValidator(new ValidEmailAndPassword($areValidEmailAndPassword));
    }
}
