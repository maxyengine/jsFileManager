<?php

namespace Nrg\Doctrine\Scope;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Nrg\Data\Abstraction\Condition;
use Nrg\Data\Abstraction\SchemaAdapter;
use Nrg\Data\Abstraction\Scope;
use Nrg\Data\Condition\DateTimeRange;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Condition\Greater;
use Nrg\Data\Condition\GreaterOrEqual;
use Nrg\Data\Condition\In;
use Nrg\Data\Condition\Less;
use Nrg\Data\Condition\LessOrEqual;
use Nrg\Data\Condition\Like;
use Nrg\Data\Condition\NotDateTimeRange;
use Nrg\Data\Condition\NotEqual;
use Nrg\Data\Condition\NotIn;
use Nrg\Data\Condition\NotLike;
use Nrg\Data\Condition\NotRange;
use Nrg\Data\Condition\Range;
use Nrg\Data\Dto\Filter;
use Nrg\Doctrine\Condition\ConditionHandler;
use Nrg\Doctrine\Condition\EqualConditionHandler;
use Nrg\Doctrine\Condition\GreaterConditionHandler;
use Nrg\Doctrine\Condition\GreaterOrEqualConditionHandler;
use Nrg\Doctrine\Condition\InConditionHandler;
use Nrg\Doctrine\Condition\LessConditionHandler;
use Nrg\Doctrine\Condition\LessOrEqualConditionHandler;
use Nrg\Doctrine\Condition\LikeConditionHandler;
use Nrg\Doctrine\Condition\NotEqualConditionHandler;
use Nrg\Doctrine\Condition\NotInConditionHandler;
use Nrg\Doctrine\Condition\NotLikeConditionHandler;
use Nrg\Doctrine\Condition\NotRangeConditionHandler;
use Nrg\Doctrine\Condition\RangeConditionHandler;
use Nrg\Doctrine\ParametersTypes;
use RuntimeException;

/**
 * Class FilterScope.
 */
class FilterScope implements Scope
{
    use ParametersTypes;

    /**
     * @var array
     */
    private $handlerMap = [
        Equal::class => EqualConditionHandler::class,
        NotEqual::class => NotEqualConditionHandler::class,
        Greater::class => GreaterConditionHandler::class,
        GreaterOrEqual::class => GreaterOrEqualConditionHandler::class,
        Less::class => LessConditionHandler::class,
        LessOrEqual::class => LessOrEqualConditionHandler::class,
        Like::class => LikeConditionHandler::class,
        NotLike::class => NotLikeConditionHandler::class,
        In::class => InConditionHandler::class,
        NotIn::class => NotInConditionHandler::class,
        Range::class => RangeConditionHandler::class,
        NotRange::class => NotRangeConditionHandler::class,
        DateTimeRange::class => RangeConditionHandler::class,
        NotDateTimeRange::class => NotRangeConditionHandler::class,
    ];

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var SchemaAdapter
     */
    private $schemaAdapter;

    /**
     * @param SchemaAdapter $schemaAdapter
     * @param Filter        $filter
     */
    public function __construct(SchemaAdapter $schemaAdapter, ?Filter $filter)
    {
        $this->filter = $filter;
        $this->schemaAdapter = $schemaAdapter;
    }

    /**
     * @param QueryBuilder $query
     *
     * @throws RuntimeException
     */
    public function apply($query): void
    {
        if (null === $this->filter) {
            return;
        }

        $this->schemaAdapter->adaptFilter($this->filter);
        $this->applyFilter($this->filter, $query);
    }

    /**
     * @param Filter                   $filter
     * @param QueryBuilder             $query
     * @param null|CompositeExpression $expression
     * @param array                    $parameters
     *
     * @throws RuntimeException
     */
    private function applyFilter(
        Filter $filter,
        QueryBuilder $query,
        CompositeExpression $expression = null,
        array &$parameters = []
    ): void {
        if ($this->filter->isEmpty()) {
            return;
        }

        if (Filter::UNION_AND === $filter->getUnion()) {
            $filterExpression = $query->expr()->andX();
        } else {
            $filterExpression = $query->expr()->orX();
        }

        foreach ($filter->getConditions() as $condition) {
            $this->applyCondition(
                $condition,
                $query,
                $filterExpression,
                $parameters
            );
        }

        foreach ($filter->getFilters() as $subFilter) {
            $this->applyFilter(
                $subFilter,
                $query,
                $filterExpression,
                $parameters
            );
        }

        if (null !== $expression) {
            $expression->add($filterExpression);
        } else {
            $query
                ->where($filterExpression)
                ->setParameters($parameters, self::getParametersTypes($parameters))
            ;
        }
    }

    /**
     * @param Condition           $condition
     * @param QueryBuilder        $query
     * @param CompositeExpression $expression
     * @param array               $parameters
     *
     * @throws RuntimeException
     */
    private function applyCondition(
        Condition $condition,
        QueryBuilder $query,
        CompositeExpression $expression,
        array &$parameters = []
    ): void {
        $this
            ->getConditionHandler($condition, $query)
            ->handle($condition, $expression)
        ;

        $parameters = array_merge(
            $parameters,
            array_values($condition->getParameters())
        );
    }

    /**
     * @param Condition    $condition
     * @param QueryBuilder $query
     *
     * @throws RuntimeException
     *
     * @return ConditionHandler
     */
    private function getConditionHandler(Condition $condition, QueryBuilder $query): ConditionHandler
    {
        $conditionClass = get_class($condition);
        $handler = $this->handlerMap[$conditionClass] ?? null;

        if (null === $handler) {
            throw new RuntimeException(sprintf('Condition handler for \'%s\' was not found', $conditionClass));
        }

        if (is_string($handler)) {
            $this->handlerMap[$conditionClass] = new $handler($query);
        }

        return $this->handlerMap[$conditionClass];
    }
}
