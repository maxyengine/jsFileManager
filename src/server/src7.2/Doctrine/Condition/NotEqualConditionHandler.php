<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;

/**
 * Class NotEqualHandler.
 */
class NotEqualConditionHandler extends ConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $expression->add($this->getQuery()->expr()->neq($condition->getField(), '?'));
    }
}
