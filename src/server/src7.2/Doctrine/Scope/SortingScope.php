<?php

namespace Nrg\Doctrine\Scope;

use Doctrine\DBAL\Query\QueryBuilder;
use Nrg\Data\Abstraction\SchemaAdapter;
use Nrg\Data\Abstraction\Scope;
use Nrg\Data\Dto\OrderBy;
use Nrg\Data\Dto\Sorting;

/**
 * Class SortingScope.
 */
class SortingScope implements Scope
{
    /**
     * @var Sorting
     */
    private $sorting;

    /**
     * @var SchemaAdapter
     */
    private $schemaAdapter;

    /**
     * @param SchemaAdapter $schemaAdapter
     * @param Sorting       $sorting
     */
    public function __construct(SchemaAdapter $schemaAdapter, ?Sorting $sorting)
    {
        $this->sorting = $sorting;
        $this->schemaAdapter = $schemaAdapter;
    }

    /**
     * @param QueryBuilder $query
     */
    public function apply($query): void
    {
        if (null === $this->sorting) {
            return;
        }

        $this->schemaAdapter->adaptSorting($this->sorting);

        /** @var OrderBy $orderBy */
        foreach ($this->sorting as $orderBy) {
            $query->addOrderBy($orderBy->getField(), $orderBy->getDirection());
        }
    }
}
