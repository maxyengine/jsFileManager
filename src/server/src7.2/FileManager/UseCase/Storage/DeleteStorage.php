<?php

namespace Nrg\FileManager\UseCase\Storage;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;
use Nrg\Utility\Abstraction\Uuid;

/**
 * Class DeleteStorage.
 *
 * Use case to delete a storage.
 */
class DeleteStorage
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
     * @throws InvalidArgumentException
     */
    public function execute(array $data): void
    {
        $storage = $this->repository->findOne((new Filter())
            ->addCondition(
                (new Equal())
                    ->setValue($data['id'])
                    ->setField('id')
            ));

        $this->repository->delete($storage);
    }
}
