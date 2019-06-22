<?php

namespace Nrg\FileManager\Entity;

use DateTime;
use JsonSerializable;
use Nrg\Data\Entity;
use Nrg\Data\Service\PopulateAbility;
use Nrg\Utility\Service\PseudoRandomUuid;

/**
 * Class Storage.
 *
 * Base storage entity implementation.
 */
abstract class Storage extends Entity implements JsonSerializable
{
    use PopulateAbility;

    public const TYPE_LOCAL = 'local';
    public const TYPE_ZIP = 'zip';
    public const TYPE_FTP = 'ftp';
    public const TYPE_SFTP = 'sftp';
    public const TYPE_AWS_S3 = 'aws-s3';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime|null
     */
    private $updatedAt;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return array
     */
    abstract public function getParams(): array;

    /**
     * @param array $params
     */
    abstract protected function setParams(array $params): void;

    /**
     * @param string $id
     *
     * @return bool
     */
    public static function isTemporary(string $id): bool
    {
        return !PseudoRandomUuid::isValidV4($id);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
                'id' => $this->getId(),
                'type' => $this->getType(),
                'name' => $this->getName(),
                'description' => $this->getDescription(),
                'createdAt' => $this->getCreatedAt()->format('Y-m-d h:i:s'),
                'updatedAt' => (null === $this->getUpdatedAt()) ? null : $this->getUpdatedAt()->format('Y-m-d H:i:s'),
            ] + $this->getParams();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    private function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $name
     */
    private function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param DateTime $createdAt
     */
    private function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    private function setUpdatedAt(?DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
