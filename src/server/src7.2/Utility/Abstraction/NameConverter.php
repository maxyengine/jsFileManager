<?php

namespace Nrg\Utility\Abstraction;

/**
 * Interface NameConverter.
 */
interface NameConverter
{
    /**
     * @param string $propertyName
     *
     * @return string
     */
    public function toSnakeCase(string $propertyName): string;

    /**
     * @param string $propertyName
     *
     * @return string
     */
    public function toCamelCase(string $propertyName): string;
}
