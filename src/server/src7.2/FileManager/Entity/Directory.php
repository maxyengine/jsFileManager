<?php

namespace Nrg\FileManager\Entity;

/**
 * Class Directory.
 *
 * Directory entity implementation.
 */
class Directory extends File
{
    /**
     * @var File[]|null
     */
    private $children;

    /**
     * @return string
     */
    public function getType(): string
    {
        return File::TYPE_DIRECTORY;
    }

    /**
     * @return File[]|null
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * @param File[] $files
     *
     * @return Directory
     */
    public function setChildren(File ...$files): Directory
    {
        $this->children = $files;

        return $this;
    }

    /**
     * @param File $child
     *
     * @return Directory
     */
    public function addChild(File $child): Directory
    {
        if (null === $this->children) {
            $this->children = [];
        }

        $this->children[] = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        if (null !== $this->children) {
            $data['children'] = $this->getChildren();
        }

        return $data;
    }
}
