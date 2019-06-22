<?php

namespace Nrg\Auth\Dto;

use JsonSerializable;
use Lcobucci\JWT\Token;

/**
 * Class LoginOutput.
 */
class LoginOutput implements JsonSerializable
{
    /**
     * @var Token
     */
    private $accessToken;

    /**
     * @var Token
     */
    private $refreshToken;

    /**
     * @param Token $accessToken
     * @param Token $refreshToken
     */
    public function __construct(Token $accessToken, Token $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return Token
     */
    public function getAccessToken(): Token
    {
        return $this->accessToken;
    }

    /**
     * @return Token
     */
    public function getRefreshToken(): Token
    {
        return $this->refreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'accessToken' => (string)$this->getAccessToken(),
            'refreshToken' => (string)$this->getRefreshToken(),
        ];
    }
}
