<?php

namespace Nrg\Auth\Abstraction;

use Lcobucci\JWT\Token;
use Nrg\Auth\Entity\User;

/**
 * Interface AuthControl
 */
interface AuthControl
{
    /**
     * @param User $user
     *
     * @return Token
     */
    public function generateAccessToken(User $user): Token;

    /**
     * @return Token
     */
    public function generateRefreshToken(User $user): Token;

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function verifyAccessToken(string $token): bool;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function verifyRefreshToken(string $token): bool;

    /**
     * @param string $token
     */
    public function setToken(string $token): void;

    /**
     * @return User
     */
    public function getUser(): User;
}