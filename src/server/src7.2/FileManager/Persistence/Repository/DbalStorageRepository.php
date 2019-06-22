<?php

namespace Nrg\FileManager\Persistence\Repository;

use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\FileManager\Persistence\Factory\StorageFactory;
use Nrg\FileManager\Persistence\Schema\StorageSchema;
use Nrg\Data\Collection;
use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Pagination;
use Nrg\Data\Dto\Sorting;
use Nrg\Data\Entity;
use Nrg\Data\Exception\EntityNotFoundException;
use Nrg\Doctrine\EntityRepository;
use Nrg\Doctrine\Scope\FilterScope;
use Nrg\Doctrine\Scope\PaginationScope;
use Nrg\Doctrine\Scope\SortingScope;

/**
 * Class SqlStorageRepository.
 *
 * Storage repository implementation.
 */
class DbalStorageRepository extends EntityRepository implements StorageRepository
{
    /**
     * @param StorageFactory $factory
     * @param StorageSchema $schema
     */
    public function __construct(StorageFactory $factory, StorageSchema $schema)
    {
        parent::__construct($schema, $factory);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Storage $storage): void
    {
        $this->getConnection()->insert(
            $this->getFullTableName(),
            $this->getFactory()->toArray($storage)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function update(Storage $storage): void
    {
        $this->getConnection()->update(
            $this->getFullTableName(),
            $this->getFactory()->toArray($storage),
            ['id' => $storage->getId()]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Storage $storage): void
    {
        $this->getConnection()->delete(
            $this->getFullTableName(),
            ['id' => $storage->getId()]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Filter $filter): bool
    {
        return false !== $this->createQuery(
                new FilterScope($this->getSchemaAdapter(), $filter)
            )
                ->select('id')
                ->from($this->getFullTableName())
                ->setMaxResults(1)
                ->execute()
                ->fetch();
    }

    /**
     * @param Filter $filter
     * @return Entity|Storage
     */
    public function findOne(Filter $filter): Storage
    {
        $data = $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter)
        )
            ->select($this->getFieldNames())
            ->from($this->getFullTableName())
            ->execute()
            ->fetch();

        if (false === $data) {
            throw new EntityNotFoundException('Entity was not found');
        }

        return $this->getFactory()->createEntity($data);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(Filter $filter = null, Sorting $sorting = null, Pagination $pagination = null): Collection
    {
        $data = $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter),
            new SortingScope($this->getSchemaAdapter(), $sorting),
            new PaginationScope($pagination)
        )
            ->select($this->getFieldNames())
            ->from($this->getFullTableName())
            ->execute()
            ->fetchAll();

        $total = $this->countAll($filter);

        return $this->getFactory()
            ->createCollection($data)
            ->setTotal($total)
            ->setPagination($pagination);
    }

    /**
     * {@inheritdoc}
     */
    public function countAll(?Filter $filter = null): int
    {
        $query = $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter)
        )
            ->select('COUNT(*)')
            ->from($this->getFullTableName());

        return $query->execute()->fetchColumn();
    }
}
