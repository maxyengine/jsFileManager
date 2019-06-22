<?php

namespace Nrg\FileManager\Persistence\Abstraction;

use Exception;
use Nrg\FileManager\Entity\Directory;
use Nrg\FileManager\Entity\File;
use Nrg\FileManager\Value\Path;

/**
 * Interface FileRepository.
 */
interface FileRepository
{
    /**
     * Creates a new directory.
     *
     * @param Directory $directory
     */
    public function createDirectory(Directory $directory): void;

    /**
     * Returns a directory by a path.
     *
     * @param Path $path
     *
     * @return Directory
     *
     * @throws Exception
     */
    public function readDirectory(Path $path): Directory;

    /**
     * @param Path $path
     */
    public function deleteDirectory(Path $path): void;

    /**
     * Creates a new file.
     *
     * @param File $file
     *
     * @throws Exception
     */
    public function createFile(File $file): void;

    /**
     * Returns a file by a path.
     *
     * @param Path $path
     *
     * @return File
     *
     * @throws Exception
     */
    public function readFile(Path $path): File;

    /**
     * Updates a file content.
     *
     * @param File $file
     *
     * @throws Exception
     */
    public function updateFile(File $file): void;

    /**
     * @param Path $path
     *
     * @throws Exception
     */
    public function deleteFile(Path $path): void;

    /**
     * @param Path $path
     * @param Path $newPath
     *
     * @throws Exception
     */
    public function copyFile(Path $path, Path $newPath): void;

    /**
     * @param Path $path
     * @param Path $newPath
     */
    public function moveFile(Path $path, Path $newPath): void;

    /**
     * @param $path
     * @param $stream
     *
     * @return bool
     *
     * @throws Exception
     */
    public function writeStream(File $file, $stream): void;

    /**
     * @param Path $path
     *
     * @return bool
     */
    public function has(Path $path): bool;
}
