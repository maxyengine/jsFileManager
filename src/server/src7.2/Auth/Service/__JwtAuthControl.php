<?php

namespace Nrg\Auth\Service;

use DateTime;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Nrg\Auth\Abstraction\AuthControl;
use Nrg\Auth\Entity\User;
use Nrg\Auth\Persistence\Abstraction\UserRepository;

/**
 * Class JwtAccessControl
 */
class JwtAuthControl implements AuthControl
{
    /**
     * @var string
     */
    private $accessTtl;

    /**
     * @var string
     */
    private $refreshTtl;

    /**
     * @var string
     */
    private $accessSignature;

    /**
     * @var string
     */
    private $refreshSignature;

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

    /**
     * @param UserRepository $userRepository
     * @param string $accessTtl
     * @param string $refreshTtl
     * @param string $accessSignature
     * @param string $refreshSignature
     */
    public function __construct(
        UserRepository $userRepository,
        string $accessTtl,
        string $refreshTtl,
        string $accessSignature,
        string $refreshSignature
    ) {

        $this->userRepository = $userRepository;
        $this->accessTtl = $accessTtl;
        $this->refreshTtl = $refreshTtl;
        $this->accessSignature = $accessSignature;
        $this->refreshSignature = $refreshSignature;
        $this->signer = new Sha256();
    }

    /**
     * {@inheritDoc}
     */
    public function generateAccessToken(User $user): Token
    {
        return (new Builder())
            ->setIssuedAt(time())
            ->setExpiration((new DateTime())->modify($this->accessTtl)->getTimestamp())
            ->set('user', $user->jsonSerialize())
            ->sign($this->signer, $this->accessSignature)
            ->getToken();
    }

    /**
     * {@inheritDoc}
     */
    public function generateRefreshToken(User $user): Token
    {
        return (new Builder())
            ->setIssuedAt(time())
            ->setExpiration((new DateTime())->modify($this->refreshTtl)->getTimestamp())
            ->set('user', $user->jsonSerialize())
            ->sign($this->signer, $this->refreshSignature)
            ->getToken();
    }

    /**
     * {@inheritDoc}
     */
    public function verifyAccessToken(string $token): bool
    {
        $accessToken = (new Parser())->parse($token);

        return $accessToken->verify($this->signer, $this->accessSignature) &&
            $accessToken->validate(new ValidationData());
    }

    /**
     * {@inheritDoc}
     */
    public function verifyRefreshToken(string $token): bool
    {
        $refreshToken = (new Parser())->parse($token);

        return $refreshToken->verify($this->signer, $this->refreshSignature) &&
            $refreshToken->validate(new ValidationData());
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