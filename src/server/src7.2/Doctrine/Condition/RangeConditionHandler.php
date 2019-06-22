<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;
use Nrg\Data\Condition\Range;

/**
 * Class RangeHandler.
 */
class RangeConditionHandler extends ConditionHandler
{
    /**
     * @param Range           $condition
     * @param CompositeExpression $expression
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        if (null === $condition->getMin()) {
            $expression->add($this->getQuery()->expr()->lte($condition->getField(), '?'));
        } elseif (null === $condition->getMax()) {
            $expression->add($this->getQuery()->expr()->gte($condition->getField(), '?'));
        } else {
            $expression->add(
                $this->getQuery()->expr()->andX(
                    $this->getQuery()->expr()->gte($condition->getField(), '?'),
                    $this->getQuery()->expr()->lte($condition->getField(), '?')
                )
            );
        }
    }
}
