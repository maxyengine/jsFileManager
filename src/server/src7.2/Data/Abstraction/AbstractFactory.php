<?php

namespace Nrg\Data\Abstraction;

use Cake\Database\Exception;
use Nrg\Data\Collection;
use Nrg\Data\Entity;

/**
 * Class AbstractFactory
 */
abstract class AbstractFactory
{
    private $schemaAdapter;

    /**
     * @param SchemaAdapter $schemaAdapter
     */
    public function __construct(SchemaAdapter $schemaAdapter)
    {
        $this->schemaAdapter = $schemaAdapter;
    }

    /**
     * @param Entity $entity
     *
     * @return array
     */
    abstract public function toArray(Entity $entity): array;

    /**
     * @param array $data
     *
     * @return Entity
     *
     * @throws Exception
     */
    abstract public function createEntity(array $data): Entity;

    /**
     * @param array $data
     *
     * @return Collection
     */
    public function createCollection(array $data): Collection
    {
        $entities = [];
        $idList = [];
        foreach ($data as $item) {
            $idList[] = $item['id'];
            $entities[] = $this->createEntity($item);
        }

        return (new Collection(...$entities))->setIdList($idList);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function adaptForDB(array $data): array
    {
        return $this->schemaAdapter->adaptDataForDB($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function adaptForApp(array $data): array
    {
        return $this->schemaAdapter->adaptDataForApp($data);
    }
}
