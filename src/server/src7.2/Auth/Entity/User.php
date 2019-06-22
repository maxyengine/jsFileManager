<?php

namespace Nrg\Auth\Entity;

use Nrg\Data\Entity;
use Nrg\Data\Service\PopulateAbility;
use JsonSerializable;

/**
 * Class User.
 */
class User extends Entity implements JsonSerializable
{
    use PopulateAbility;

    /**
     * @var string
     */
    private $email;

    /**
     * @var null|string
     */
    private $password;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
        ];

        return $data;
    }

    /**
     * @param string $email
     */
    private function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param null|string $password
     */
    private function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
