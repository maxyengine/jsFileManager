<?php

namespace Nrg\FileManager\Entity;

use DateTime;
use JsonSerializable;
use Nrg\FileManager\Value\Path;
use Nrg\FileManager\Value\Permissions;
use Nrg\FileManager\Value\Size;

/**
 * Class File.
 *
 * File entity implementation.
 */
class File implements JsonSerializable
{
    public const TYPE_FILE = 'file';
    public const TYPE_DIRECTORY = 'directory';
    public const TYPE_HYPERLINK = 'hyperlink';

    /**
     * @var Path
     */
    private $path;

    /**
     * @var string
     */
    private $type = self::TYPE_FILE;

    /**
     * @var string|null
     */
    private $mimeType;

    /**
     * @var Size|null
     */
    private $size;

    /**
     * @var Permissions|null
     */
    private $permissions;

    /**
     * @var DateTime|null
     */
    private $lastModified;

    /**
     * @var null|string
     */
    private $contents;

    /**
     * @param Path $path
     */
    public function __construct(Path $path)
    {
        $this->setPath($path);
    }

    /**
     * @return Path
     */
    public function getPath(): Path
    {
        return $this->path;
    }

    /**
     * @param Path $path
     *
     * @return File
     */
    public function setPath(Path $path): File
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return File
     */
    public function setType(string $type): File
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Size|null
     */
    public function getSize(): ?Size
    {
        return $this->size;
    }

    /**
     * @param Size $size
     *
     * @return File
     */
    public function setSize(Size $size): File
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Permissions|null
     */
    public function getPermissions(): ?Permissions
    {
        return $this->permissions;
    }

    /**
     * @param Permissions $permissions
     *
     * @return File
     */
    public function setPermissions(Permissions $permissions): File
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastModified(): ?DateTime
    {
        return $this->lastModified;
    }

    /**
     * @param DateTime $lastModified
     *
     * @return File
     */
    public function setLastModified(DateTime $lastModified): File
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getContents(): ?string
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     *
     * @return File
     */
    public function setContents(string $contents): File
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType(string $mimeType): File
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = [
            'path' => $this->getPath()->getValue(),
            'type' => $this->getType(),
        ];

        if (null !== $this->contents) {
            $data['contents'] = $this->getContents();
        }

        if (null !== $this->size) {
            $data['size'] = $this->getSize()->getValue();
        }

        if (null !== $this->permissions) {
            $data['permissions'] = (string)$this->getPermissions();
        }

        if (null !== $this->lastModified) {
            $data['lastModified'] = $this->getLastModified()->getTimestamp();
        }

        return $data;
    }
}
