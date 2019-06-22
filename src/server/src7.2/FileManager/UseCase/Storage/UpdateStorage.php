<?php

namespace Nrg\FileManager\UseCase\Storage;

use DateTime;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;
use Nrg\Utility\Abstraction\Uuid;

/**
 * Class UpdateStorage.
 *
 * Use case to create a new file.
 */
class UpdateStorage
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
     */
    public function execute(array $data): Storage
    {
        $storage = $this->createEntity($data['id']);
        $storage->populateObject(['updatedAt' => new DateTime()] + $data);

        $this->repository->update($storage);

        return $storage;
    }

    /**
     * @param string $type
     *
     * @return Storage
     */
    private function createEntity(string $id): Storage
    {
        return $this->repository->findOne(
            (new Filter())
                ->addCondition(
                    (new Equal())
                        ->setValue($id)
                        ->setField('id')
                )
        );
    }
}
