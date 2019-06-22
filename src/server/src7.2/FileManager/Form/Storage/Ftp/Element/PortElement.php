<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Validator\IsIntegerValidator;

class PortElement extends Element
{
    public function __construct()
    {
        parent::__construct('port');

        $this->addValidator(new IsIntegerValidator());
    }
}