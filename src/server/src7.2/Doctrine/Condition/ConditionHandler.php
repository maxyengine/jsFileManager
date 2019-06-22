<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Nrg\Data\Abstraction\Condition;

abstract class ConditionHandler
{
    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @param Condition           $condition
     * @param CompositeExpression $expression
     */
    abstract public function handle(Condition $condition, CompositeExpression $expression): void;

    /**
     * @param QueryBuilder $query
     */
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    /**
     * @return QueryBuilder
     */
    protected function getQuery(): QueryBuilder
    {
        return $this->query;
    }
}
