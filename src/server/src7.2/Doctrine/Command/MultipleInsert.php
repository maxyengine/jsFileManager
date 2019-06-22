<?php

namespace Nrg\Doctrine\Command;

use Doctrine\DBAL\DBALException;
use Nrg\Doctrine\Abstraction\Connection;

/**
 * Class MultipleInsert.
 */
class MultipleInsert
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array[]
     */
    private $values = [];

    /**
     * @var array
     */
    private $types = [];

    /**
     * MultipleInsert constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $tableName
     *
     * @return MultipleInsert
     */
    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param array $columns
     * @param array $types
     *
     * @return MultipleInsert
     */
    public function setColumns(array $columns, array $types = []): self
    {
        $this->columns = $columns;
        $this->types = $types;

        return $this;
    }

    /**
     * @param array $values
     *
     * @return MultipleInsert
     */
    public function addValues(array $values): self
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * @throws DBALException
     *
     * @return int
     */
    public function execute(): int
    {
        $sets = [];
        $values = [];
        foreach ($this->values as $valueList) {
            $sets[] = '(' . implode(',', array_fill(0, count($valueList), '?')) . ')';
            $values = array_merge($values, $valueList);
        }

        $columns = implode(',', $this->columns);
        $sets = implode(',', $sets);
        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES {$sets}";

        return $this->connection->executeUpdate($sql, $values);
    }
}
