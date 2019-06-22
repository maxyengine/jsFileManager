<?php

namespace Nrg\Auth\UseCase;

use Nrg\Auth\Abstraction\AuthControl;
use Nrg\Auth\Entity\User;
use Nrg\Auth\Dto\LoginOutput;

/**
 * Class CreateLoginOutput
 */
class CreateLoginOutput
{
    /**
     * @var AuthControl
     */
    private $authControl;

    /**
     * @param AuthControl $authControl
     */
    public function __construct(AuthControl $authControl)
    {
        $this->authControl = $authControl;
    }

    /**
     * @param User|null $user
     *
     * @return LoginOutput
     */
    public function execute(User $user = null): LoginOutput
    {
        return new LoginOutput(
            $this->authControl->generateAccessToken($user ?? $this->authControl->getUser()),
            $this->authControl->generateRefreshToken($user ?? $this->authControl->getUser())
        );
    }
}
