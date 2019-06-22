<?php

namespace Nrg\Doctrine;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Nrg\Data\Collection;
use Nrg\Data\Dto\Filter;
use Nrg\Data\Dto\Pagination;
use Nrg\Data\Dto\Sorting;
use Nrg\Data\Entity;
use Nrg\Doctrine\Abstraction\EntityRepository;
use Nrg\Data\Exception\EntityNotFoundException;
use Nrg\Doctrine\Scope\FilterScope;
use Nrg\Doctrine\Scope\PaginationScope;
use Nrg\Doctrine\Scope\SortingScope;
use PDO;
use RuntimeException;

/**
 * Class DbalEntityRepository
 */
class DbalEntityRepository extends \Nrg\Doctrine\EntityRepository implements EntityRepository
{
    use ParametersTypes;

    /**
     * @param Entity $entity
     */
    public function create(Entity $entity): void
    {
        $this->getConnection()->insert(
            $this->getFullTableName(),
            $this->getFactory()->toArray($entity),
            $this->getFieldTypes()
        );
    }

    /**
     * @param string $id
     *
     * @throws EntityNotFoundException
     *
     * @return Entity
     */
    public function read(string $id): Entity
    {
        $data = $this->createQuery()
            ->select($this->getFieldNames())
            ->from($this->getFullTableName())
            ->where('id = :id')
            ->setParameter('id', $id, PDO::PARAM_STR)
            ->execute()
            ->fetch()
        ;

        if (false === $data) {
            throw new EntityNotFoundException('Entity was not found');
        }

        return $this->getFactory()->createEntity($data);
    }

    /**
     * @param Entity $entity
     */
    public function update(Entity $entity): void
    {
        $this->getConnection()->update(
            $this->getFullTableName(),
            $this->getFactory()->toArray($entity),
            ['id' => $entity->getId()],
            $this->getFieldTypes()
        );
    }

    /**
     * @param Entity $entity
     *
     * @throws InvalidArgumentException
     */
    public function delete(Entity $entity): void
    {
        $this->getConnection()->delete(
            $this->getFullTableName(),
            ['id' => $entity->getId()]
        );
    }

    /**
     * @param array  $data
     * @param Filter $filter
     *
     * @throws RuntimeException
     */
    public function updateAll(array $data, Filter $filter): void
    {
        $data = $this->getSchemaAdapter()->adaptDataForDB($data);

        $query = $this->createQuery();
        (new FilterScope($this->getSchemaAdapter(), $filter))->apply($query);

        $parameters = [];
        $types = [];

        foreach ($data as $fieldName => $value) {
            $query->set($fieldName, '?');
            $parameters[] = $value;
            $types[] = $this->getSchema()->getFieldType($fieldName);
        }

        $query
            ->update($this->getFullTableName())
            ->setParameters(
                array_merge($parameters, $query->getParameters()),
                array_merge($types, $query->getParameterTypes())
            )
            ->execute()
        ;
    }

    /**
     * @param Filter $filter
     *
     * @throws RuntimeException
     */
    public function deleteAll(Filter $filter): void
    {
        // TODO: needed?
        if ($filter->isEmpty()) {
            throw new RuntimeException('Filter is empty');
        }

        $query = $this->createQuery();
        (new FilterScope($this->getSchemaAdapter(), $filter))->apply($query);

        $query
            ->delete($this->getFullTableName())
            ->setParameters(
                $query->getParameters(),
                $query->getParameterTypes()
            )
            ->execute()
        ;
    }

    /**
     * @param Filter $filter
     *
     * @throws EntityNotFoundException
     * @throws RuntimeException
     *
     * @return Entity
     */
    public function findOne(Filter $filter): Entity
    {
        $data = $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter)
        )
            ->select($this->getFieldNames())
            ->from($this->getFullTableName())
            ->execute()
            ->fetch();
    ;

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


        $total = $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter)
        )
            ->select('COUNT(id)')
            ->from($this->getFullTableName())
            ->execute()
            ->fetchColumn();

        return $this->getFactory()
            ->createCollection($data)
            ->setTotal($total)
            ->setPagination($pagination);
    }

    /**
     * @param Filter|null $filter
     *
     * @return int
     */
    public function countAll(?Filter $filter = null): int
    {
        return $this->createQuery(
            new FilterScope($this->getSchemaAdapter(), $filter)
        )
            ->select('COUNT(id)')
            ->from($this->getFullTableName())
            ->execute()
            ->fetchColumn();
    }

    /**
     * @param Filter $filter
     *
     * @throws RuntimeException
     *
     * @return bool
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
}
