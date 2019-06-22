<?php

namespace Nrg\FileManager\UseCase\File;

use DateTime;
use Exception;
use function GuzzleHttp\Psr7\str;
use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\FileManager\Value\Path;
use Nrg\FileManager\Entity\File;
use Nrg\FileManager\Value\Permissions;
use Nrg\FileManager\Value\Size;
use Nrg\Data\Exception\EntityNotFoundException;

/**
 * Class UploadFile.
 *
 * Service to upload file.
 */
class UploadFile
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
     * @return File
     * @throws Exception
     */
    public function execute(array $data): File
    {
        $path = new Path($data['path'], true);
        $uploadedFile = $data['file'];

        if (!$this->repository->has($path)) {
            throw new EntityNotFoundException(sprintf('Directory \'%s\' is not exists or it\'s not readable', $path));
        }

        $filePath = $this->getUniquePath($path->join($uploadedFile->getName()));

        $file = (new File($filePath))
            ->setSize(new Size($uploadedFile->getSize()))
            ->setType($uploadedFile->getType())
            ->setPermissions(new Permissions(0755))
            ->setLastModified(new DateTime());

        $stream = fopen($uploadedFile->getTmpName(), 'r+');

        $this->repository->writeStream($file, $stream);

        return $file;
    }

    private function getUniquePath(Path $path): Path
    {
        $i = 1;
        $start = mb_strlen($path->getFileName()->getExtension()) + 1;
        $uniquePath = $path;
        while ($this->repository->has($uniquePath)) {
            $uniquePath = new Path(substr_replace((string)$path, "({$i})", -$start, 0));
            $i++;
        }

        return $uniquePath;
    }
}
