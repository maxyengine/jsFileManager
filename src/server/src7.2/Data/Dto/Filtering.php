<?php

namespace Nrg\Data\Dto;

use Nrg\Data\Abstraction\Condition;
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
use Nrg\Data\Service\PopulateAbility;

class Filtering
{
    use PopulateAbility;

    public const LITERAL_PROPERTY_UNION = 'union';
    public const LITERAL_PROPERTY_CONDITIONS = 'conditions';
    public const LITERAL_PROPERTY_NAME = 'name';

    public const CONDITION_EQUAL = 'equal';
    public const CONDITION_NOT_EQUAL = 'notEqual';
    public const CONDITION_RANGE = 'range';
    public const CONDITION_NOT_RANGE = 'notRange';
    public const CONDITION_DATE_TIME_RANGE = 'dateTimeRange';
    public const CONDITION_NOT_DATE_TIME_RANGE = 'notDateTimeRange';
    public const CONDITION_GREATER = 'greater';
    public const CONDITION_GREATER_OR_EQUAL = 'greaterOrEqual';
    public const CONDITION_LESS = 'less';
    public const CONDITION_LESS_OR_EQUAL = 'lessOrEqual';
    public const CONDITION_IN = 'in';
    public const CONDITION_NOT_IN = 'notIn';
    public const CONDITION_LIKE = 'like';
    public const CONDITION_NOT_LIKE = 'notLike';

    /**
     * @var array
     */
    public const CONDITION_CLASS_MAP = [
        self::CONDITION_EQUAL => Equal::class,
        self::CONDITION_NOT_EQUAL => NotEqual::class,
        self::CONDITION_RANGE => Range::class,
        self::CONDITION_NOT_RANGE => NotRange::class,
        self::CONDITION_DATE_TIME_RANGE => DateTimeRange::class,
        self::CONDITION_NOT_DATE_TIME_RANGE => NotDateTimeRange::class,
        self::CONDITION_GREATER => Greater::class,
        self::CONDITION_GREATER_OR_EQUAL => GreaterOrEqual::class,
        self::CONDITION_LESS => Less::class,
        self::CONDITION_LESS_OR_EQUAL => LessOrEqual::class,
        self::CONDITION_IN => In::class,
        self::CONDITION_NOT_IN => NotIn::class,
        self::CONDITION_LIKE => Like::class,
        self::CONDITION_NOT_LIKE => NotLike::class,
    ];

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->filter = new Filter();
        $this->populateObject($data);
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    private function getCondition(array $condition): Condition
    {
        $class = self::CONDITION_CLASS_MAP[$condition[self::LITERAL_PROPERTY_NAME]];
        unset($condition[self::LITERAL_PROPERTY_NAME]);

        return new $class($condition);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public static function isFilter(array $data): bool
    {
        return isset($data[self::LITERAL_PROPERTY_UNION], $data[self::LITERAL_PROPERTY_CONDITIONS]);
    }

    /**
     * @param array $data
     */
    private function setFilter(array $data): void
    {
        $this->filter = $this->buildFilter($data);
    }

    /**
     * @param array $data
     *
     * @return Filter
     */
    private function buildFilter(array $data): Filter
    {
        if (empty($data)) {
            return new Filter();
        }

        $filter = new Filter($data[self::LITERAL_PROPERTY_UNION]);

        foreach ($data[self::LITERAL_PROPERTY_CONDITIONS] as $item) {
            if (self::isFilter($item)) {
                $filter->addFilter($this->buildFilter($item));
            } else {
                $filter->addCondition($this->getCondition($item));
            }
        }

        return $filter;
    }
}
