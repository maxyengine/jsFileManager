<?php

namespace Nrg\Doctrine\Abstraction;

use Nrg\Data\Collection;
use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Pagination;
use Nrg\Data\Dto\Sorting;
use Nrg\Data\Entity;

/**
 * Interface EntityRepository.
 */
interface EntityRepository
{
    /**
     * @param Entity $entity
     */
    public function create(Entity $entity): void;

    /**
     * @param string $id
     *
     * @return Entity
     */
    public function read(string $id): Entity;

    /**
     * @param Entity $entity
     */
    public function update(Entity $entity): void;

    /**
     * @param Entity $entity
     */
    public function delete(Entity $entity): void;

    /**
     * @param array  $data
     * @param Filter $filter
     */
    public function updateAll(array $data, Filter $filter): void;

    /**
     * @param Filter $filter
     */
    public function deleteAll(Filter $filter): void;

    /**
     * @param Filter $filter
     *
     * @return bool
     */
    public function exists(Filter $filter): bool;

    /**
     * @param Filter|null $filter
     *
     * @return int
     */
    public function countAll(Filter $filter = null): int;

    /**
     * @param Filter $filter
     *
     * @return Entity
     */
    public function findOne(Filter $filter): Entity;

    /**
     * @param null|Filter     $filter
     * @param null|Sorting    $sorting
     * @param null|Pagination $pagination
     *
     * @return Collection
     */
    public function findAll(Filter $filter = null, Sorting $sorting = null, Pagination $pagination = null): Collection;
}
