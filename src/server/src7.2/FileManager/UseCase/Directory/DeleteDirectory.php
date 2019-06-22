<?php

namespace Nrg\FileManager\UseCase\Directory;

use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;

/**
 * Class DeleteDirectory.
 *
 * Service to delete file.
 */
class DeleteDirectory
{
    /**
     * @var FileRepository
     */
    private $repository;


    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @example for the data:
     * [
     *  'path' => '/folder1/folder2',  // required
     * ]
     *
     * @param array $data
     *
     */
    public function execute(array $data): void
    {
        $path = new Path($data['path']);

        $this->repository->deleteDirectory($path);
    }
}
