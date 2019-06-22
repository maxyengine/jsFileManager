<?php

namespace Nrg\FileManager\UseCase\File;

use Exception;
use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;

/**
 * Class DeleteFile.
 *
 * Service to delete file.
 */
class DeleteFile
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
     * @param array $data
     *
     * @example for the data:
     * [
     *  'path' => '/folder1/file.txt',  // required
     * ]
     *
     * @throws Exception
     */
    public function execute(array $data): void
    {
        $path = new Path($data['path']);

        $this->repository->deleteFile($path);
    }
}
