<?php

namespace Nrg\FileManager\Persistence\Repository;

use DateTime;
use Exception;
use function GuzzleHttp\Psr7\str;
use League\Flysystem\Directory as FlysystemDirectory;
use League\Flysystem\FileExistsException;
use League\Flysystem\MountManager;
use League\Flysystem\RootViolationException;
use Nrg\FileManager\Entity\Directory;
use Nrg\FileManager\Entity\File;
use Nrg\FileManager\Entity\Hyperlink;
use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;
use Nrg\FileManager\Value\Permissions;
use Nrg\FileManager\Value\Size;
use RuntimeException;

/**
 * Class FlysystemFileRepository.
 *
 * Flysystem file repository implementation.
 *
 * @see https://github.com/thephpleague/flysystem
 */
abstract class FlysystemFileRepository implements FileRepository
{
    /**
     * @var MountManager
     */
    private $mountManager;
    /**
     * @var array
     */
    private $filter;

    /**
     * @param string $storageId
     *
     * @return FileRepository
     */
    abstract protected function mountStorage(string $storageId): FlysystemFileRepository;

    /**
     * @param array $filter
     */
    public function __construct(array $filter = [])
    {
        $this->mountManager = new MountManager();
        $this->filter = $filter;
    }

    public function has(Path $path): bool
    {
        $this->mountStorage($path->getStorageId());

        return $path->isRoot() || $this->getMountManager()->has((string)$path);
    }

    /**
     * {@inheritdoc}
     */
    public function readDirectory(Path $path): Directory
    {
        $this->mountStorage($path->getStorageId());

        return $this->createEntity($path->getStorageId(), $this->getMetadata($path));
    }

    /**
     * {@inheritdoc}
     */
    public function createDirectory(Directory $directory): void
    {
        $this->mountStorage($directory->getPath()->getStorageId());

        $result = $this->getMountManager()->createDir((string)$directory->getPath());

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during creating the directory \'%s\'', $directory->getPath())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function readFile(Path $path): File
    {
        $this->mountStorage($path->getStorageId());
        $contents = $this->getMountManager()->read((string)$path);

        return $this
            ->createEntity($path->getStorageId(), $this->getMetadata($path))
            ->setContents($contents);
    }

    /**
     * {@inheritdoc}
     */
    public function createFile(File $file): void
    {
        $this->mountStorage($file->getPath()->getStorageId());

        $result = $this->getMountManager()->write((string)$file->getPath(), $file->getContents());

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during creating the file \'%s\'', $file->getPath())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateFile(File $file): void
    {
        $this->mountStorage($file->getPath()->getStorageId());

        $result = $this->getMountManager()->update((string)$file->getPath(), $file->getContents());

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during updating the file \'%s\'', $file->getPath())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFile(Path $path, Path $newPath): void
    {
        $this
            ->mountStorage($path->getStorageId())
            ->mountStorage($newPath->getStorageId());

        try {
            $result = $this->getMountManager()->copy((string)$path, (string)$newPath);
        } catch (FileExistsException $e) {
            throw new RuntimeException($e->getMessage());
        }

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during copying the file \'%s\'', $path)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function moveFile(Path $path, Path $newPath): void
    {
        $this
            ->mountStorage($path->getStorageId())
            ->mountStorage($newPath->getStorageId());

        $result = $this->getMountManager()->move((string)$path, (string)$newPath);

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during moving the file \'%s\'', $path)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function writeStream(File $file, $stream): void
    {
        $this->mountStorage($file->getPath()->getStorageId());

        $result = $this->getMountManager()->writeStream((string)$file->getPath(), $stream);

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during uploading the file \'%s\'', $file->getPath())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile(Path $path): void
    {
        $this->mountStorage($path->getStorageId());

        $result = $this->getMountManager()->delete((string)$path);

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during deleting the file \'%s\'', $path)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDirectory(Path $path): void
    {
        $this->mountStorage($path->getStorageId());

        try {
            $result = $this->getMountManager()->deleteDir((string)$path);
        } catch (RootViolationException $e) {
            throw new RuntimeException($e->getMessage());
        }

        if (false === $result) {
            throw new RuntimeException(
                sprintf('Error occurred during deleting the directory \'%s\'', $path)
            );
        }
    }

    /**
     * @param Path $path
     *
     * @return array
     */
    private function getMetadata(Path $path): array
    {
        if ($path->isRoot()) {
            return [
                'type' => File::TYPE_DIRECTORY,
                'path' => $path->getFilePath(),
                'children' => $this->getMountManager()->listContents((string)$path),
            ];
        }

        $file = $this->getMountManager()->get((string)$path);

        if ($file instanceof FlysystemDirectory) {
            return [
                'type' => File::TYPE_DIRECTORY,
                'path' => $path->getFilePath(),
                'children' => $file->getContents(),
            ];
        } else {
            return [
                    'path' => $path->getFilePath(),
                    'type' => $this->determineEntityType($file->getMetadata()),
                    'mimeType' => $file->getMimetype(),
                ] + $file->getMetadata();
        }
    }

    /**
     * @param array $file
     *
     * @return File|Directory
     * @throws Exception
     */
    private function createEntity(string $storageId, array $file): File
    {
        switch ($file['type']) {
            case File::TYPE_DIRECTORY:
                $entity = new Directory(Path::create($storageId, $file['path'], true));

                if (isset($file['children'])) {
                    $entity->setChildren();
                    foreach ($file['children'] as $child) {
                        $child['type'] = $this->determineEntityType($child);
                        $childEntity = $this->createEntity($storageId, $child);
                        if (!in_array((string)$childEntity->getPath()->getFileName(), $this->filter)) {
                            $entity->addChild($this->createEntity($storageId, $child));
                        }
                    }
                }
                break;
            case File::TYPE_HYPERLINK:
                $entity = new Hyperlink(Path::create($storageId, $file['path']));
                break;
            default:
                $entity = new File(Path::create($storageId, $file['path']));
        }

        if (isset($file['type'])) {
            $entity->setType($file['type']);
        }

        if (isset($file['mimeType'])) {
            $entity->setMimeType($file['mimeType']);
        }

        if (isset($file['size'])) {
            $entity->setSize(new Size((int)$file['size']));
        }

        if (isset($file['permissions'])) {
            $entity->setPermissions(new Permissions($file['permissions']));
        }

        if (isset($file['timestamp'])) {
            $entity->setLastModified(new DateTime(date('Y-m-d H:i:s', $file['timestamp'])));
        }

        return $entity;
    }

    private function determineEntityType($raw)
    {
        if ('dir' === $raw['type']) {
            return File::TYPE_DIRECTORY;
        }

        $extension = $raw['extension'] ?? pathinfo($raw['path'], PATHINFO_EXTENSION);

        if (in_array($extension, ['https', 'http'])) {
            return File::TYPE_HYPERLINK;
        }

        return File::TYPE_FILE;
    }

    /**
     * @return MountManager
     */
    protected function getMountManager(): MountManager
    {
        return $this->mountManager;
    }
}
