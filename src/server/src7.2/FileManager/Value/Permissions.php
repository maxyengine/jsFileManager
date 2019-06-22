<?php

namespace Nrg\FileManager\Value;

use InvalidArgumentException;

/**
 * Class Permissions.
 *
 * File permissions entity implementation.
 */
class Permissions
{
    /**
     * @var int
     */
    private $value;

    /**
     * Permissions constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->setValue($value);
    }

    /**
     * Creates entity from octal string.
     *
     * @param string $value
     *
     * @return Permissions
     */
    public static function createOfOctalString(string $value): Permissions
    {
        return new self(octdec($value));
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '0'.(string) decoct($this->getValue() & 0777);
    }

    /**
     * Checks if value for file permissions is valid.
     *
     * @param int $value
     *
     * @return bool
     */
    private function isValid(int $value): bool
    {
        return $value >= 0 && $value <= 0777;
    }

    /**
     * @param int $value
     */
    private function setValue(int $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid permissions were provided. They must be in range 0..0777');
        }

        $this->value = $value;
    }
}
