<?php

namespace Nrg\FileManager\UseCase\File;

use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;

/**
 * Class MoveFile.
 *
 * Service to move file.
 */
class MoveFile
{
    private const OVERWRITE = false;

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
     *  'path' => '/folder1/file.txt',  // required
     *  'newPath' => '/folder2/file.txt',  // required
     *  'overwrite' => false,  // optional
     * ]
     *
     * @param array $data
     *
     */
    public function execute(array $data): void
    {
        $path = new Path($data['path']);
        $newPath = new Path($data['newPath']);
        $overwrite = $data['overwrite'] ?? self::OVERWRITE;

        if ($path->isEqual($newPath)) {
            return;
        }

        if ($this->repository->has($newPath)) {
            if (!$overwrite) {
                $this->repository->deleteFile($path);
                return;
            }

            $this->repository->deleteFile($newPath);
        }

        $this->repository->moveFile($path, $newPath);
    }
}
