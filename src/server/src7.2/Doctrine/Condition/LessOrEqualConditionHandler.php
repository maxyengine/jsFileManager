<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;

/**
 * Class LessOrEqualHandler.
 */
class LessOrEqualConditionHandler extends ConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $expression->add($this->getQuery()->expr()->lte($condition->getField(), '?'));
    }
}
