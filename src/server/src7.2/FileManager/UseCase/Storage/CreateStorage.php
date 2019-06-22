<?php

namespace Nrg\FileManager\UseCase\Storage;

use DateTime;
use DomainException;
use Exception;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Entity\Storage\FtpStorage;
use Nrg\FileManager\Entity\Storage\LocalStorage;
use Nrg\FileManager\Entity\Storage\SftpStorage;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Utility\Abstraction\Uuid;

/**
 * Class CreateStorage.
 *
 * Use case to create a new file.
 */
class CreateStorage
{
    /**
     * @var StorageRepository
     */
    private $repository;

    /**
     * @var Uuid
     */
    private $uuid;

    public function __construct(StorageRepository $repository, Uuid $uuid)
    {
        $this->repository = $repository;
        $this->uuid = $uuid;
    }

    /**
     * @param array $data
     *
     * @return Storage
     *
     * @throws Exception
     */
    public function execute(array $data, string $type): Storage
    {
        $storage = $this->createEntity($type);
        $storage->populateObject(['createdAt' => new DateTime()] + $data);

        $this->repository->create($storage);

        return $storage;
    }

    /**
     * @param string $type
     *
     * @return Storage
     *
     * @throws Exception
     */
    private function createEntity(string $type): Storage
    {
        switch ($type) {
            case Storage::TYPE_LOCAL:
                return new LocalStorage($this->uuid->generateV4());
            case Storage::TYPE_FTP:
                return new FtpStorage($this->uuid->generateV4());
            case Storage::TYPE_SFTP:
                return new SftpStorage($this->uuid->generateV4());
        }

        throw new DomainException('unknown type of storage was provided');
    }
}
