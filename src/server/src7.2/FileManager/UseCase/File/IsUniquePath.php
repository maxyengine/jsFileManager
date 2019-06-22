<?php

namespace Nrg\FileManager\UseCase\File;

use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;

/**
 * Class IsUniquePath
 */
class IsUniquePath
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        return !$this->repository->has(new Path($data['path']));
    }
}