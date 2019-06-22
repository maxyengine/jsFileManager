<?php

namespace Nrg\FileManager\Entity\Storage;

use Nrg\FileManager\Entity\Storage;

/**
 * Class LocalStorage.
 *
 * LocalStorage entity implementation.
 */
class LocalStorage extends Storage
{
    /**
     * @var string
     */
    private $root;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_LOCAL;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'root' => $this->getRoot(),
        ];
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     */
    protected function setRoot(string $root): void
    {
        $this->root = $root;
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params): void
    {
        $this->root = $params['root'];
    }
}
