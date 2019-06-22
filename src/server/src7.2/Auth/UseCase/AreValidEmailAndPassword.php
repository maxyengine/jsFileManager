<?php

namespace Nrg\Auth\UseCase;

use Nrg\Auth\Dto\LoginInput;
use Nrg\Auth\Persistence\Abstraction\UserRepository;
use Nrg\Data\Exception\EntityNotFoundException;

/**
 * Class AreValidEmailAndPassword
 */
class AreValidEmailAndPassword
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(LoginInput $input): bool
    {
        try {
            $user = $this->userRepository->findByEmail($input->getEmail());
        } catch (EntityNotFoundException $e) {
            return false;
        }

        return $input->getPassword() === $user->getPassword();
    }
}