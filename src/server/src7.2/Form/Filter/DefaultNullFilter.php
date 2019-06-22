<?php

namespace Nrg\Form\Filter;

use Nrg\Form\Abstraction\Filter;
use Nrg\Form\Element;

class DefaultNullFilter implements Filter
{
    public function apply(Element $element): void
    {
        if ($element->hasValue() && $element->hasEmptyValue()) {
            $element->setValue(null);
        }
    }
}
