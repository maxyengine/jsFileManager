<?php

namespace Nrg\FileManager\UseCase\Storage;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Persistence\Abstraction\StorageRepository;
use Nrg\Data\Condition\Equal;
use Nrg\Data\Dto\Filter;
use Nrg\Utility\Abstraction\Uuid;

/**
 * Class StorageDetails.
 *
 * Use case to get storage details.
 */
class StorageDetails
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

    public function execute(array $data): Storage
    {
        return $this->repository->findOne(
            (new Filter())
                ->addCondition(
                    (new Equal())
                        ->setValue($data['id'])
                        ->setField('id')
                )
        );
    }
}
