<?php

namespace Nrg\Doctrine;

use Doctrine\DBAL\Connection;
use PDO;

/**
 * Trait ParametersTypes.
 * todo: Replace solution on Schema::getTypes() {return ['name': PDO::STRING,'age': PDO::INTEGER,];}
 */
trait ParametersTypes
{
    /**
     * @param array $parameters
     *
     * @return array
     */
    private static function getParametersTypes(array $parameters): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            $result[] = self::getParameterType($parameter);
        }

        return $result;
    }

    /**
     * @param $parameter
     *
     * @return int
     */
    private static function getParameterType($parameter): int
    {
        if (is_array($parameter)) {
            switch (gettype(current($parameter))) {
                case 'integer':
                    return Connection::PARAM_INT_ARRAY;
                case 'string':
                default:
                    return Connection::PARAM_STR_ARRAY;
            }
        } else {
            switch (gettype($parameter)) {
                case 'integer':
                    return PDO::PARAM_INT;
                case 'boolean':
                    return PDO::PARAM_BOOL;
                case 'string':
                default:
                    return PDO::PARAM_STR;
            }
        }
    }
}
