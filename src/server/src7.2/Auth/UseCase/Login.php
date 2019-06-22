<?php

namespace Nrg\Auth\UseCase;

use Nrg\Auth\Dto\LoginInput;
use Nrg\Auth\Dto\LoginOutput;
use Nrg\Auth\Persistence\Abstraction\UserRepository;

/**
 * Class Login
 */
class Login
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CreateLoginOutput
     */
    private $createLoginOutput;

    /**
     * @param UserRepository $userRepository
     * @param CreateLoginOutput $createLoginOutput
     */
    public function __construct(UserRepository $userRepository, CreateLoginOutput $createLoginOutput)
    {
        $this->userRepository = $userRepository;
        $this->createLoginOutput = $createLoginOutput;
    }

    /**
     * @param LoginInput $input
     *
     * @return LoginOutput
     */
    public function execute(LoginInput $input): LoginOutput
    {
        return $this->createLoginOutput->execute(
            $this->userRepository->findByEmail($input->getEmail())
        );
    }
}
