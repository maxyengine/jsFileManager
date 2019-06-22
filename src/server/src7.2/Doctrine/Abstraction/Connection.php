<?php

namespace Nrg\Doctrine\Abstraction;

use Doctrine\DBAL\Driver\Connection as DBALConnection;
use Doctrine\DBAL\Exception\InvalidArgumentException;

interface Connection extends DBALConnection
{
    /**
     * Creates a new instance of a SQL query builder.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQueryBuilder();

    /**
     * @param $tableExpression
     * @param array $data
     * @param array $types
     *
     * @return mixed
     */
    public function insert($tableExpression, array $data, array $types = []);

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression  The expression of the table to update quoted or unquoted.
     * @param array  $data       An associative array containing column-value pairs.
     * @param array  $identifier The update criteria. An associative array containing column-value pairs.
     * @param array  $types      Types of the merged $data and $identifier arrays in that order.
     *
     * @return integer The number of affected rows.
     */
    public function update($tableExpression, array $data, array $identifier, array $types = array());

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string $tableExpression  The expression of the table on which to delete.
     * @param array  $identifier The deletion criteria. An associative array containing column-value pairs.
     * @param array  $types      The types of identifiers.
     *
     * @return integer The number of affected rows.
     *
     * @throws InvalidArgumentException
     */
    public function delete($tableExpression, array $identifier, array $types = array());
}
