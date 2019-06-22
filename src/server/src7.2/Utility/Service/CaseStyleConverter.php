<?php

namespace Nrg\Utility\Service;

/**
 * Class CaseStyleConverter.
 */
class CaseStyleConverter
{
    private const SNAKE_CASE_DELIMITER = '_';

    /**
     * @param string $propertyName
     *
     * @return string
     */
    public function toSnakeCase(string $propertyName): string
    {
        return strtolower(
            preg_replace(
                ['/([A-Z]+)/', '/_([A-Z]+)([A-Z][a-z])/'],
                ['_$1', '_$1_$2'],
                lcfirst($propertyName)
            )
        );
    }

    /**
     * @param string $propertyName
     *
     * @return string
     */
    public function toCamelCase(string $propertyName): string
    {
        return str_replace(
            self::SNAKE_CASE_DELIMITER,
            '',
            lcfirst(ucwords($propertyName, self::SNAKE_CASE_DELIMITER))
        );
    }
}
