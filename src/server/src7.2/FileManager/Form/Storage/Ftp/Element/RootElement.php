<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\IsStringValidator;
use Nrg\Form\Validator\LengthValidator;

class RootElement extends Element
{
    public function __construct()
    {
        parent::__construct('root');

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMin(1)
                    ->setMax(255)
            );
    }
}