<?php

namespace Nrg\Data\Filter;

use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;

/**
 * Class CustomNameFilter.
 */
class CustomFieldNameFilter extends Filter
{
    /**
     * @param string $filedName
     * @param string $value
     */
    public function __construct(string $filedName, string $value)
    {
        parent::__construct();

        $this->addCondition(
            (new Equal())
                ->setValue($value)
                ->setField($filedName)
        );
    }
}
