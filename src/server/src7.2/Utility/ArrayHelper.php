<?php

namespace Nrg\Utility;

/**
 * Class ArrayHelper
 *
 * Utility to work with arrays.
 */
class ArrayHelper
{
    public static function contains(array $what, array $where): bool
    {
        foreach ($what as $key => $value) {
            if (!array_key_exists($key, $where)) {
                return false;
            }

            if (is_array($value)) {
                if (!is_array($where[$key]) || !self::contains($value, $where[$key])) {
                    return false;
                }

                continue;
            }

            if ($value !== $where[$key]) {
                return false;
            }
        }

        return true;
    }
}
