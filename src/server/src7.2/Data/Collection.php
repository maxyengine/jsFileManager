<?php

namespace Nrg\Data;

use ArrayIterator;
use Countable;
use Nrg\Data\Dto\Pagination;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Collection.
 */
class Collection implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var Entity[]
     */
    private $data = [];

    /**
     * @var array
     */
    private $idList = [];

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * @param Entity ...$entities
     */
    public function __construct(Entity ...$entities)
    {
        $this
            ->setData($entities)
            ->setTotal($this->count())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getData());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = [
            'total' => $this->getTotal(),
            'data' => $this->getData(),
        ];

        if (null !== $this->pagination) {
            $data += [
                'limit' => $this->getPagination()->getLimit(),
                'offset' => $this->getPagination()->getOffset(),
            ];
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return Collection
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param int $total
     *
     * @return Collection
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @param Pagination $pagination
     *
     * @return Collection
     */
    public function setPagination(?Pagination $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->getData());
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getIdList(): array
    {
        return $this->idList;
    }

    /**
     * @param array $idList
     *
     * @return Collection
     */
    public function setIdList(array $idList): Collection
    {
        $this->idList = $idList;

        return $this;
    }

    /**
     * @return int
     */
    private function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return Pagination
     */
    private function getPagination(): Pagination
    {
        return $this->pagination;
    }
}
