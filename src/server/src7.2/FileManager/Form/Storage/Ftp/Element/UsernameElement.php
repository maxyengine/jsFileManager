<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\LengthValidator;
use Nrg\Form\Validator\IsStringValidator;

class UsernameElement extends Element
{
    public function __construct()
    {
        parent::__construct('username');

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMin(1)
                    ->setMax(100)
            );
    }
}