<?php

namespace Nrg\Data\Filter;

use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;

/**
 * Class NameFilter.
 */
class NameFilter extends Filter
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct();

        $this->addCondition(
            (new Equal())
                ->setValue($name)
                ->setField('name')
        );
    }
}
