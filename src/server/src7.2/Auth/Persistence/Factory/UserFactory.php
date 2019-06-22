<?php

namespace Nrg\Auth\Persistence\Factory;

use Nrg\Auth\Entity\User;
use Nrg\Data\Entity;

/**
 * Class UserFactory.
 */
class UserFactory
{
    /**
     * @param Entity|User $entity
     *
     * @return array
     */
    public function toArray(Entity $entity): array
    {
        return [
            'id' => $entity->getId(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
        ];
    }

    /**
     * @param array $data
     * @return User
     */
    public function createEntity(array $data): User
    {
        $entity = new User($data['id']);
        $entity->populateObject($data);

        return $entity;
    }
}
