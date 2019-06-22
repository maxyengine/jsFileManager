<?php

namespace Nrg\FileManager\UseCase\Storage;

use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Condition\NotEqual;
use Nrg\Data\Dto\Filter;

/**
 * Class IsUniqueName
 */
class IsUniqueName
{
    /**
     * @var StorageRepository
     */
    private $storageRepository;

    /**
     * @param StorageRepository $storageRepository
     */
    public function __construct(StorageRepository $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    public function execute(string $name, string $exceptedId = null): bool
    {
        $filter = (new Filter())
            ->addCondition(
                (new Equal())
                    ->setValue($name)
                    ->setField('name')
            );

        if (null !== $exceptedId) {
            $filter
                ->addCondition(
                    (new NotEqual())
                        ->setValue($exceptedId)
                        ->setField('id')
                );
        }

        return !$this->storageRepository->exists($filter);
    }
}