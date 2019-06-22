<?php

namespace Nrg\FileManager\Value;

use InvalidArgumentException;

/**
 * Class FileName.
 *
 * File name entity implementation.
 */
class FileName
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $baseName;

    /**
     * @var string
     */
    private $extension;

    /**
     * FileName constructor.
     *
     * @param string $value
     * @param bool $isDirectory
     */
    public function __construct(string $value, bool $isDirectory = false)
    {
        $this->setValue($value, $isDirectory);
    }

    public static function create(string $path, bool $isDirectory = false): FileName
    {
        return new self(self::extract($path), $isDirectory);
    }

    public static function extract(string $path): string
    {
        $position = mb_strrpos($path, '/');

        return false === $position ? $path : (string)mb_substr($path, $position + 1);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getBaseName(): string
    {
        return $this->baseName;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Checks if file name equals to string.
     *
     * @param string $id
     *
     * @return bool
     */
    public function isEqual(string $id): bool
    {
        return $this->getValue() === $id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Checks if value for file name is valid.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return !preg_match('/(\/|\\\\|\*|\?|\"|>|<|\|)/iu', $value);
    }

    /**
     * @param string $value
     * @param bool $isDirectory
     */
    private function setValue(string $value, bool $isDirectory)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid file name was provided');
        }

        $this->value = $value;

        $extension = '';
        $baseName = $value;

        if (!$isDirectory) {
            $position = mb_strrpos($value, '.');
            $extension = false === $position ? '' : (string)mb_substr($value, $position + 1);
            if ($extension) {
                $baseName = substr($baseName, 0, -strlen($extension) - 1);
            }
        }

        $this->extension = $extension;
        $this->baseName = $baseName;
    }
}
