<?php

namespace Nrg\FileManager\UseCase\Storage;

use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Data\Collection;
use Nrg\Data\Dto\Metadata;

/**
 * Class ListStorage.
 */
class StorageList
{
    /**
     * @var StorageRepository
     */
    private $repository;

    public function __construct(StorageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     *
     * @return Collection
     */
    public function execute(array $data): Collection
    {
        $metadata = new Metadata($data);

        return $this->repository->findAll(
            $metadata->getFilter(),
            $metadata->getSorting(),
            $metadata->getPagination()
        );
    }
}
