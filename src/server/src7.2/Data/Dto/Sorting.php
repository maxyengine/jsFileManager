<?php

namespace Nrg\Data\Dto;

use ArrayIterator;
use Nrg\Data\Service\PopulateAbility;
use IteratorAggregate;

/**
 * Class Sorting.
 */
class Sorting implements IteratorAggregate
{
    use PopulateAbility;

    /**
     * @var OrderBy[]
     */
    private $orderBy = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populateObject($data);
    }

    /**
     * @return OrderBy[]
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getOrderBy());
    }

    /**
     * @param array $orderBy
     */
    private function setOrderBy(array $orderBy): void
    {
        foreach ($orderBy as $item) {
            $this->orderBy[] = new OrderBy($item);
        }
    }
}
