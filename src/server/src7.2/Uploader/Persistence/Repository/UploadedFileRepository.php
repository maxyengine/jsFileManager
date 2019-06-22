<?php

namespace Nrg\Uploader\Persistence\Repository;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Nrg\FileManager\Entity\Storage\LocalStorage;
use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Persistence\Repository\FlysystemFileRepository;
use Nrg\Utility\Abstraction\Config;

/**
 * Class UploadedFileRepository.
 */
class UploadedFileRepository extends FlysystemFileRepository
{
    /**
     * @var LocalStorage
     */
    private $storage;

    /**
     * @param Config $config
     * @param string $id
     * @param array $filter
     */
    public function __construct(Config $config, string $id, array $filter = [])
    {
        parent::__construct($filter);

        $this->storage = new LocalStorage($id);
        $this->storage->populateObject(['root' => $config->get('uploadsFolder')]);
    }

    /**
     * @param string $storageId
     *
     * @return FileRepository
     */
    protected function mountStorage(string $storageId): FlysystemFileRepository
    {
        $filesystem = new Filesystem(new Local($this->storage->getRoot()));
        $this->getMountManager()->mountFilesystem($this->storage->getId(), $filesystem);

        return $this;
    }
}
