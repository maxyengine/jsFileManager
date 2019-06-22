<?php

namespace Nrg\FileManager\UseCase\File;

use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;

/**
 * Class IsFileExists.
 *
 * Service to check if a file exists.
 */
class IsFileExists
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
     * Reads a file by path.
     *
     * data [
     *  'path' => '/folder1/file.txt', // required
     * ]
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        return $this->repository->has(new Path($data['path']));
    }
}
