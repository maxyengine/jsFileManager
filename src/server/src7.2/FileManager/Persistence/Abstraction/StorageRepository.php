<?php

namespace Nrg\FileManager\Persistence\Abstraction;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Nrg\FileManager\Entity\Storage;
use Nrg\Data\Collection;
use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Pagination;
use Nrg\Data\Dto\Sorting;
use Nrg\Data\Exception\EntityNotFoundException;
use RuntimeException;

/**
 * Interface StorageRepository.
 */
interface StorageRepository
{
    /**
     * Creates a new storage.
     *
     * @param Storage $storage
     */
    public function create(Storage $storage): void;

    /**
     * @param Storage $storage
     */
    public function update(Storage $storage): void;

    /**
     * @param Storage $storage
     *
     * @throws InvalidArgumentException
     */
    public function delete(Storage $storage): void;

    /**
     * @param Filter $filter
     *
     * @throws RuntimeException
     *
     * @return bool
     */
    public function exists(Filter $filter): bool;

    /**
     * @param null|Filter $filter
     * @param null|Sorting $sorting
     * @param null|Pagination $pagination
     *
     * @return Collection
     */
    public function findAll(Filter $filter = null, Sorting $sorting = null, Pagination $pagination = null): Collection;

    /**
     * @param Filter $filter
     *
     * @throws EntityNotFoundException
     *
     * @return Storage
     */
    public function findOne(Filter $filter): Storage;

    /**
     * @param Filter|null $filter
     *
     * @return int
     */
    public function countAll(?Filter $filter = null): int;
}
