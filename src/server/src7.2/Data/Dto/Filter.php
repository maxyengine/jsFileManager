<?php

namespace Nrg\Data\Dto;

use Nrg\Data\Abstraction\Condition;

class Filter
{
    public const UNION_AND = 'and';
    public const UNION_OR = 'or';

    /**
     * @var Condition[]
     */
    private $conditions = [];

    /**
     * @var Filter[]
     */
    private $filters = [];

    /**
     * @var string
     */
    private $union;

    /**
     * @param string $union
     */
    public function __construct(string $union = self::UNION_AND)
    {
        $this->union = $union;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->getConditions()) && empty($this->getFilters());
    }

    /**
     * @param Condition $condition
     *
     * @return Filter
     */
    public function addCondition(Condition $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @param Filter $filter
     *
     * @return Filter
     */
    public function addFilter(self $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return string
     */
    public function getUnion(): string
    {
        return $this->union;
    }
}
