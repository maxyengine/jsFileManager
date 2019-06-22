<?php

namespace Nrg\Http\Value;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class UploadedFile.
 *
 * HTTP uploaded file implementation.
 */
class UploadedFile
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $type;

    /**
     * @var null|string
     */
    private $tmpName;

    /**
     * @var null|int
     */
    private $error;

    /**
     * @var null|int
     */
    private $size;

    /**
     * @var bool
     */
    private $moved = false;

    /**
     * @var string
     */
    private $extension;

    /**
     * UploadedFile constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $tmpName
     * @param int $error
     * @param int $size
     */
    public function __construct(string $name, string $type, string $tmpName, int $error, int $size)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
        $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getTmpName(): ?string
    {
        return $this->tmpName;
    }

    /**
     * @return null|string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * Moves file to target path after checks.
     *
     * @param string $targetPath
     */
    public function moveTo(string $targetPath)
    {
        if (UPLOAD_ERR_OK !== $this->error) {
            throw new RuntimeException('Cannot move file, the file has been loaded with an error');
        }
        if (empty($targetPath)) {
            throw new InvalidArgumentException('Invalid targetPath provided, must be a non-empty string');
        }
        if ($this->moved) {
            throw new RuntimeException('Cannot move file after it has already been moved');
        }
        if (empty(PHP_SAPI) || 0 === strpos(PHP_SAPI, 'cli')) {
            rename($this->tmpName, $targetPath);
        } else {
            if (!is_uploaded_file($this->tmpName)) {
                throw new RuntimeException('Cannot move file it was not uploaded via HTTP POST');
            }
            if (false === move_uploaded_file($this->tmpName, $targetPath)) {
                throw new RuntimeException('Error occurred during move operation');
            }
        }
        $this->moved = true;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->name;
    }
}
