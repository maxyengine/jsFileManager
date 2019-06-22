<?php

namespace Nrg\Utility\Abstraction;

use Exception;

interface Uuid
{
    /**
     * @return string
     *
     * @throws Exception
     */
    public static function generateV4(): string;

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValidV4(string $value): bool;
}
