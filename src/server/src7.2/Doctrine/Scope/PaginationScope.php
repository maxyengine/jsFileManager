<?php

namespace Nrg\Doctrine\Scope;

use Doctrine\DBAL\Query\QueryBuilder;
use Nrg\Data\Abstraction\Scope;
use Nrg\Data\Dto\Pagination;

/**
 * Class ScopePagination.
 */
class PaginationScope implements Scope
{
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @param Pagination $pagination
     */
    public function __construct(?Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param QueryBuilder $query
     */
    public function apply($query): void
    {
        if (null === $this->pagination) {
            return;
        }

        $query
            ->setFirstResult($this->pagination->getOffset())
            ->setMaxResults($this->pagination->getLimit())
        ;
    }
}
