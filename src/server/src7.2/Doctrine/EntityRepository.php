<?php

namespace Nrg\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Nrg\Data\Abstraction\AbstractFactory;
use Nrg\Data\Abstraction\AbstractSchema;
use Nrg\Data\Abstraction\SchemaAdapter;
use Nrg\Data\Abstraction\Scope;
use Nrg\Doctrine\Abstraction\Connection;

/**
 * Class EntityRepository.
 */
class EntityRepository
{
    use ParametersTypes;

    /**
     * @var AbstractSchema
     */
    private $schema;

    /**
     * @var AbstractFactory
     */
    private $factory;

    /**
     * @param AbstractSchema  $schema
     * @param AbstractFactory $factory
     */
    public function __construct(AbstractSchema $schema, AbstractFactory $factory)
    {
        $this->schema = $schema;
        $this->factory = $factory;
    }

    /**
     * @param Scope ...$scopes
     *
     * @return QueryBuilder
     */
    protected function createQuery(Scope ...$scopes): QueryBuilder
    {
        $query = $this->getConnection()->createQueryBuilder();

        foreach ($scopes as $scope) {
            $scope->apply($query);
        }

        return $query;
    }

    /**
     * @return AbstractSchema
     */
    protected function getSchema(): AbstractSchema
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    protected function getFullTableName(): string
    {
        return $this->getSchema()->getFullTableName();
    }

    /**
     * @return array
     */
    protected function getFieldNames(): array
    {
        return $this->getSchema()->getFieldNames();
    }

    /**
     * @return array
     */
    protected function getFieldTypes(): array
    {
        return $this->getSchema()->getFieldTypes();
    }

    /**
     * @param string $filedName
     *
     * @return int
     */
    protected function getFieldType(string $filedName): int
    {
        return $this->getSchema()->getFieldType($filedName);
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return $this->getSchema()->getConnection();
    }

    /**
     * @return SchemaAdapter
     */
    protected function getSchemaAdapter(): SchemaAdapter
    {
        return $this->getSchema()->getAdapter();
    }

    /**
     * @return AbstractFactory
     */
    protected function getFactory(): AbstractFactory
    {
        return $this->factory;
    }
}
