<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;

/**
 * Class GreaterOrEqualHandler.
 */
class GreaterOrEqualConditionHandler extends ConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $expression->add($this->getQuery()->expr()->gte($condition->getField(), '?'));
    }
}
