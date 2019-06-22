<?php

namespace Nrg\Data\Element;

use Nrg\Data\Dto\Pagination;
use Nrg\Form\Element;
use Nrg\Form\Filter\TrimFilter;
use Nrg\Form\Validator\Between;
use Nrg\Form\Validator\ValueInteger;

/**
 * Class Offset.
 */
class Offset extends Element
{
    public function __construct(string $name = 'offset')
    {
        parent::__construct($name);
        $this
            ->addFilter(new TrimFilter())
            ->addValidator((new ValueInteger()))
            ->addValidator((new Between())
                ->setMinValue(Pagination::MIN_OFFSET)
            )
        ;
    }
}
