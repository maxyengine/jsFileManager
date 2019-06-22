<?php

namespace Nrg\FileManager\Value;

use InvalidArgumentException;
use LogicException;

/**
 * Class Path.
 *
 * File path implementation.
 */
class Path
{
    private const STORAGE_SEPARATOR = '://';

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $storageId;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var FileName
     */
    private $fileName;

    /**
     * @var bool
     */
    private $isDirectory;

    /**
     * @param string $value
     * @param bool $isDirectory
     */
    public function __construct(string $value, bool $isDirectory = false)
    {
        $this->setValue($value, $isDirectory);
    }

    /**
     * @param string $storageId
     * @param string $filePath
     * @param bool $isDirectory
     *
     * @return Path
     */
    public static function create(string $storageId, string $filePath, bool $isDirectory = false): Path
    {
        return new self($storageId.self::STORAGE_SEPARATOR.$filePath, $isDirectory);
    }

    /**
     * @return string
     */
    public function getStorageId(): string
    {
        return $this->storageId;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return FileName
     */
    public function getFileName(): FileName
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Joins a string to a file path.
     *
     * @param string $value
     * @param bool $isDirectory
     *
     * @return Path
     */
    public function join(string $value, bool $isDirectory = false): Path
    {
        if (!$this->isDirectory()) {
            throw new LogicException('This method can be called only on directory');
        }

        $separator = substr($this->getValue(), -1) === '/' ? '' : '/';

        return new self($this->getValue().$separator.trim($value, '/'), $isDirectory);
    }

    /**
     * Checks if a file is directory.
     *
     * @return bool
     */
    public function isDirectory(): bool
    {
        return $this->isDirectory;
    }

    /**
     * Checks if a file is root of storage.
     *
     * @return bool
     */
    public function isRoot(): bool
    {
        return empty($this->filePath);
    }

    /**
     * Checks if a files are equal.
     *
     * @return bool
     */
    public function isEqual(string $path): bool
    {
        return $this->getValue() === $path;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @param string $value
     * @param bool $isDirectory
     */
    private function setValue(string $value, bool $isDirectory): void
    {
        $value = $this->filterValue($value);

        if (!self::isValid($value)) {
            throw new InvalidArgumentException('Invalid path was provided');
        }

        list($this->storageId, $this->filePath) = explode(self::STORAGE_SEPARATOR, $value, 2);

        $this->value = $value;
        $this->fileName = FileName::create($value, $isDirectory);
        $this->isDirectory = $isDirectory;
    }

    private function filterValue(string $value): string
    {
        $position = strpos($value, '->');

        if (false === $position) {
            return $value;
        }

        return trim(substr($value, 0, $position));
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        if (strpos($value, '://') < 1) {
            return false;
        }

        list($storageId, $filePath) = explode(self::STORAGE_SEPARATOR, $value, 2);

        return
            self::isValidStorageId($storageId) &&
            self::isValidFilePath($filePath) &&
            FileName::isValid(FileName::extract($filePath));
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private static function isValidStorageId(string $value): bool
    {
        return true;
    }

    /**
     * Checks if a value is a valid file path.
     *
     * @param string $value
     *
     * @return bool
     */
    private static function isValidFilePath(string $value): bool
    {
        return empty($value) || !preg_match('/\/\.{1,2}\//iu', '/'.$value.'/');
    }
}
