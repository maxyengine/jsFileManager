<?php

namespace Nrg\Data\Abstraction;

use Nrg\Doctrine\Abstraction\Connection;
use PDO;

/**
 * Class AbstractionSchema.
 */
abstract class AbstractSchema
{
    private const SCHEMA_SEPARATOR = '.';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SchemaAdapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $name;

    /**
     * @param Connection $connection
     * @param SchemaAdapter $adapter
     * @param string|null $name
     */
    public function __construct(Connection $connection, SchemaAdapter $adapter, string $name = null)
    {
        $this->connection = $connection;
        $this->adapter = $adapter;
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getFieldTypes(): array
    {
        return [];
    }

    /**
     * @param string $fieldName
     *
     * @return int
     */
    public function getFieldType(string $fieldName): int
    {
        return $this->getFieldTypes()[$fieldName] ?? PDO::PARAM_STR;
    }

    /**
     * @return array
     */
    abstract public function getFieldNames(): array;

    /**
     * @return string
     */
    public function getFullTableName(): string
    {
        return null === $this->name ? $this->getTableName() : $this->name.self::SCHEMA_SEPARATOR.$this->getTableName();
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @return SchemaAdapter
     */
    public function getAdapter(): SchemaAdapter
    {
        return $this->adapter;
    }

    /**
     * @return string
     */
    abstract protected function getTableName(): string;
}
