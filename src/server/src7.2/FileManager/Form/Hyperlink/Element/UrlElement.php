<?php

namespace Nrg\FileManager\Form\Hyperlink\Element;

use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\IsUrlValidator;

class UrlElement extends Element
{
    public function __construct(string $name = 'url')
    {
        parent::__construct($name);

        $this
            ->addFilter(new TrimFilter())
            ->addValidator(new IsUrlValidator());
    }
}