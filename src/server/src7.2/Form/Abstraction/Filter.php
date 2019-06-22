<?php

namespace Nrg\Form\Abstraction;

use Nrg\Form\Element;

interface Filter
{
    public function apply(Element $element): void;
}
