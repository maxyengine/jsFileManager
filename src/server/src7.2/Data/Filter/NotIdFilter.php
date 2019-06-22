<?php

namespace Nrg\Data\Filter;

use Nrg\Data\Condition\NotEqual;
use Nrg\Data\Dto\Filter;

/**
 * Class NotIdFilter.
 */
class NotIdFilter extends Filter
{
    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();

        $this->addCondition(
            (new NotEqual())
                ->setValue($id)
                ->setField('id')
        );
    }
}
