<?php

namespace Nrg\Auth\Persistence\Abstraction;

use Nrg\Auth\Entity\User;
use Nrg\Data\Exception\EntityNotFoundException;

/**
 * Interface UserRepository.
 */
interface UserRepository
{
    /**
     * @param string $email
     *
     * @return User
     *
     * @throws EntityNotFoundException
     */
    public function findByEmail(string $email): User;
}
