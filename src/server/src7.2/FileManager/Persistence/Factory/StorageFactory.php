<?php

namespace Nrg\FileManager\Persistence\Factory;

use DateTime;
use Nrg\FileManager\Entity\Storage\FtpStorage;
use Nrg\FileManager\Entity\Storage\LocalStorage;
use Nrg\FileManager\Entity\Storage;
use Nrg\FileManager\Entity\Storage\SftpStorage;
use Nrg\Data\Entity;
use Nrg\Data\Abstraction\AbstractFactory;

/**
 * Class StorageFactory.
 */
class StorageFactory extends AbstractFactory
{
    /**
     * @param Entity|Storage $entity
     *
     * @return array
     */
    public function toArray(Entity $entity): array
    {
        $array = [
            'id' => $entity->getId(),
            'type' => $entity->getType(),
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
            'params' => json_encode($entity->getParams(), JSON_UNESCAPED_SLASHES),
            'createdAt' => $entity->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => null !== $entity->getUpdatedAt() ? $entity->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ];

        return $this->adaptForDB($array);
    }

    /**
     * {@inheritdoc}
     */
    public function createEntity(array $data): Entity
    {
        $data = $this->adaptForApp($data);

        $data['params'] = json_decode($data['params'], true);
        $data['createdAt'] = new DateTime($data['createdAt']);
        $data['updatedAt'] = null !== $data['updatedAt'] ? new DateTime($data['updatedAt']) : null;

        $entity = $this->createStorage($data);
        $entity->populateObject($data);

        return $entity;
    }

    private function createStorage(array $data): Storage
    {
        switch ($data['type']) {
            case Storage::TYPE_LOCAL:
                return new LocalStorage($data['id']);
            case Storage::TYPE_FTP:
                return new FtpStorage($data['id']);
            case Storage::TYPE_SFTP:
                return new SftpStorage($data['id']);
        }
    }
}
