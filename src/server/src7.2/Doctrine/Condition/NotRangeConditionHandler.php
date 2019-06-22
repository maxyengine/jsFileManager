<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;
use Nrg\Data\Condition\NotRange;

/**
 * Class NotRangeHandler.
 */
class NotRangeConditionHandler extends ConditionHandler
{
    /**
     * @param NotRange           $condition
     * @param CompositeExpression $expression
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        if (null === $condition->getMin()) {
            $expression->add($this->getQuery()->expr()->gt($condition->getField(), '?'));
        } elseif (null === $condition->getMax()) {
            $expression->add($this->getQuery()->expr()->lt($condition->getField(), '?'));
        } else {
            $expression->add(
                $this->getQuery()->expr()->orX(
                    $this->getQuery()->expr()->lt($condition->getField(), '?'),
                    $this->getQuery()->expr()->gt($condition->getField(), '?')
                )
            );
        }
    }
}
