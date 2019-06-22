<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;

/**
 * Class EqualHandler.
 */
class GreaterConditionHandler extends ConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $expression->add($this->getQuery()->expr()->gt($condition->getField(), '?'));
    }
}
