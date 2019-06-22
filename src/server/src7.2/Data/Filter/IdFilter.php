<?php

namespace Nrg\Data\Filter;

use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;

/**
 * Class IdFilter.
 */
class IdFilter extends Filter
{
    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();

        $this->addCondition(
            (new Equal())
                ->setValue($id)
                ->setField('id')
        );
    }
}
