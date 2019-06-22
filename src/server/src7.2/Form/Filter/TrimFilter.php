<?php

namespace Nrg\Form\Filter;

use Nrg\Form\Abstraction\Filter;
use Nrg\Form\Element;

class TrimFilter implements Filter
{
    public function apply(Element $element): void
    {
        if ($element->hasValue() && is_string($element->getValue())) {
            $element->setValue(trim($element->getValue()));
        }
    }
}
