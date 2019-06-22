<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;
use Nrg\Data\Condition\Equal;

/**
 * Class EqualHandler.
 */
class EqualConditionHandler extends ConditionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $expression->add($this->getQuery()->expr()->eq($condition->getField(), '?'));
    }
}
