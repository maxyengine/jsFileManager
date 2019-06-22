<?php

namespace Nrg\FileManager\Persistence\Factory;

use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Entity\Storage\LocalStorage;
use Nrg\FileManager\Entity\Storage\ZipStorage;

/**
 * Class TempStorageFactory.
 */
class TempStorageFactory
{
    private const SEPARATOR = ':';

    private const TYPE_TEMP = 'temp';
    private const TYPE_TEMP_ZIP = 'temp-zip';

    /**
     * @var string
     */
    private $root;

    /**
     * @param string $root
     */
    public function __construct(string $root)
    {
        $this->root = $root;
    }

    /**
     * @param string $id
     *
     * @return Storage
     */
    public function createById(string $id): Storage
    {
        $data = explode(self::SEPARATOR, $id, 2);

        $type = $data[0];
        $path = $data[1] ?? null;

        switch ($type) {
            case self::TYPE_TEMP:
                $storage = new LocalStorage($id);
                $storage->populateObject(['root' => $this->root]);

                return $storage;
            case self::TYPE_TEMP_ZIP:
                $storage = new ZipStorage($id);
                $storage->populateObject(['location' => $this->root.'/'.$path]);

                return $storage;
        }
    }
}
