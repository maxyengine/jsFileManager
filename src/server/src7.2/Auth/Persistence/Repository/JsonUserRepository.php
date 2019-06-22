<?php

namespace Nrg\Auth\Persistence\Repository;

use Nrg\Auth\Entity\User;
use Nrg\Auth\Persistence\Abstraction\UserRepository;
use Nrg\Auth\Persistence\Factory\UserFactory;
use Nrg\Data\Exception\EntityNotFoundException;
use RuntimeException;

/**
 * Class JsonUserRepository
 */
class JsonUserRepository implements UserRepository
{
    /**
     * @var UserFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $data;

    /**
     * @param UserFactory $factory
     * @param string $path
     */
    public function __construct(UserFactory $factory, string $path)
    {
        $this->factory = $factory;
        $this->data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid json file format.');
        }
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function findByEmail(string $email): User
    {
        foreach ($this->data as $id => $user) {
            if ($user['email'] === $email) {
                return $this->factory->createEntity($user + ['id' => $id]);
            }
        }

        throw new EntityNotFoundException('User was not found');
    }
}
