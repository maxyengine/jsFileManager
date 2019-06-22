<?php

namespace Nrg\Doctrine\Condition;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Nrg\Data\Abstraction\Condition;
use Nrg\Data\Condition\Like;

/**
 * Class LikeHandler.
 */
class LikeConditionHandler extends ConditionHandler
{
    /**
     * @param Like           $condition
     * @param CompositeExpression $expression
     */
    public function handle(Condition $condition, CompositeExpression $expression): void
    {
        $condition->setValue(
            Like::convertTypeOperandToMask(
                $condition->getValue(),
                $condition->getTypeOperand(),
                $condition->isForceCaseInsensitivity()
            )
        );

        $expression->add(
            $this->getQuery()->expr()->like(
                $condition->isForceCaseInsensitivity() ? $condition->getField() : 'lower('.$condition->getField().')',
                '?'
            )
        );
    }
}
