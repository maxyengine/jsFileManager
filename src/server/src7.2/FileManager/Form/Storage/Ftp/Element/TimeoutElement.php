<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Validator\IsIntegerValidator;

class TimeoutElement extends Element
{
    public function __construct()
    {
        parent::__construct('timeout');

        $this->addValidator(new IsIntegerValidator());
    }
}