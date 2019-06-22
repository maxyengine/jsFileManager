<?php

namespace Nrg\FileManager\Form\Storage\Sftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\IsStringValidator;
use Nrg\Form\Validator\LengthValidator;

class PrivateKeyElement extends Element
{
    public function __construct()
    {
        parent::__construct('privateKey');

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsStringValidator())
            ->addValidator(
                (new LengthValidator())
                    ->setMax(4096)
            );
    }
}