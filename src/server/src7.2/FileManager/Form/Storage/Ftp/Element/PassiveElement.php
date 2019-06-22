<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Validator\IsBooleanValidator;

class PassiveElement extends Element
{
    public function __construct()
    {
        parent::__construct('passive');

        $this->addValidator(new IsBooleanValidator());
    }
}