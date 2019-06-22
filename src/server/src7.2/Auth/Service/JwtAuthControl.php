<?php

namespace Nrg\Auth\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Nrg\Auth\Abstraction\AuthControl;
use Nrg\Auth\Entity\User;
use Nrg\Auth\Persistence\Abstraction\UserRepository;
use Nrg\Utility\Abstraction\Config;

/**
 * Class JwtAccessControl
 */
class JwtAuthControl implements AuthControl
{
    /**
     * @var string
     */
    private $ttl;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var Sha256
     */
    private $signer;

    /**
     * @var Token
     */
    private $accessToken;

    /**
     * @var User
     */
    private $user;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository,
        Config $config,
        string $signature = null
    ) {
        $this->userRepository = $userRepository;
        $this->signature = $signature ?? $config->get('signature');
        $this->signer = new Sha256();
    }

    /**
     * {@inheritDoc}
     */
    public function generateAccessToken(User $user): Token
    {
        return (new Builder())
            ->setIssuedAt(time())
            ->set('user', $user->jsonSerialize())
            ->sign($this->signer, $this->signature)
            ->getToken();
    }

    /**
     * {@inheritDoc}
     */
    public function generateRefreshToken(User $user): Token
    {
        return $this->generateAccessToken($user);
    }

    /**
     * {@inheritDoc}
     */
    public function verifyAccessToken(string $token): bool
    {
        $accessToken = (new Parser())->parse($token);

        return $accessToken->verify($this->signer, $this->signature) &&
            $accessToken->validate(new ValidationData());
    }

    /**
     * {@inheritDoc}
     */
    public function verifyRefreshToken(string $token): bool
    {
        return $this->verifyAccessToken($token);
    }

    /**
     * {@inheritDoc}
     */
    public function setToken(string $token): void
    {
        $this->accessToken = (new Parser())->parse($token);
        $this->user = $this->userRepository->findByEmail($this->accessToken->getClaim('user')->email);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser(): User
    {
        return $this->user;
    }
}