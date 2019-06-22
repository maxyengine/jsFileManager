<?php

namespace Nrg\FileManager\Persistence\Repository;

use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Entity\Storage\LocalStorage;
use Nrg\FileManager\Entity\Storage\ZipStorage;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\FileManager\Persistence\Factory\TempStorageFactory;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;

/**
 * Class MultiStorageFileRepository
 */
class MultiStorageFileRepository extends FlysystemFileRepository
{
    /**
     * @var StorageRepository
     */
    private $storageRepository;

    /**
     * @var TempStorageFactory
     */
    private $tempStorageFactory;

    /**
     * @param StorageRepository $storageRepository
     * @param TempStorageFactory $tempStorageFactory
     */
    public function __construct(StorageRepository $storageRepository, TempStorageFactory $tempStorageFactory)
    {
        parent::__construct();

        $this->storageRepository = $storageRepository;
        $this->tempStorageFactory = $tempStorageFactory;
    }

    /**
     * {@inheritDoc}
     */
    protected function mountStorage(string $storageId): FlysystemFileRepository
    {
        if (Storage::isTemporary($storageId)) {
            $storage = $this->tempStorageFactory->createById($storageId);
        } else {
            $storage = $this->storageRepository->findOne(
                (new Filter())
                    ->addCondition(
                        (new Equal())
                            ->setValue($storageId)
                            ->setField('id')
                    )
            );
        }

        /**
         * @var $storage LocalStorage|ZipStorage
         */
        switch ($storage->getType()) {
            case Storage::TYPE_LOCAL:
                $filesystem = new Filesystem(new Local($storage->getRoot()));
                break;
            case Storage::TYPE_ZIP:
                $filesystem = new Filesystem(new ZipArchiveAdapter($storage->getLocation()));
                break;
            case Storage::TYPE_FTP:
                $filesystem = new Filesystem(new Ftp($storage->getParams()));
                break;
            case Storage::TYPE_SFTP:
                $filesystem = new Filesystem(new SftpAdapter($storage->getParams()));
                break;
        }

        $this->getMountManager()->mountFilesystem($storage->getId(), $filesystem);

        return $this;
    }
}
