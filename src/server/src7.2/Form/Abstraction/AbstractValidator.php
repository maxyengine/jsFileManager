<?php

namespace Nrg\Form\Abstraction;

use Nrg\Form\ErrorAbility;

/**
 * Class AbstractValidator.
 */
abstract class AbstractValidator implements Validator
{
    use ErrorAbility;
}
