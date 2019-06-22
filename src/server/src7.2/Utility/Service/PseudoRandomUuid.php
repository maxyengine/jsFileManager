<?php

namespace Nrg\Utility\Service;

use Nrg\Utility\Abstraction\Uuid;
use Exception;

class PseudoRandomUuid implements Uuid
{
    /**
     * {@inheritdoc}
     */
    public static function generateV4(): string
    {
        try {
            $data = random_bytes(16);
        } catch (Exception $e) {
            throw new Exception('Appropriate source of randomness cannot be found');
        }
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return sprintf('%s%s-%s-%s-%s-%s%s%s', ...str_split(bin2hex($data), 4));
    }

    /**
     * {@inheritdoc}
     */
    public static function isValidV4(string $value): bool
    {
        return 1 === preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
    }
}
