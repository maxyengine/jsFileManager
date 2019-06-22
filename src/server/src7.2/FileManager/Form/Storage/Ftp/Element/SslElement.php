<?php

namespace Nrg\FileManager\Form\Storage\Ftp\Element;

use Nrg\Form\Element;
use Nrg\Form\Validator\IsBooleanValidator;

class SslElement extends Element
{
    public function __construct()
    {
        parent::__construct('ssl');

        $this->addValidator(new IsBooleanValidator());
    }
}