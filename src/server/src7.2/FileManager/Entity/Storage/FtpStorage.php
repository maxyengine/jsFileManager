<?php

namespace Nrg\FileManager\Entity\Storage;

use Nrg\FileManager\Entity\Storage;

/**
 * Class FtpStorage.
 *
 * FtpStorage entity implementation.
 */
class FtpStorage extends Storage
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var string|null
     */
    private $root;

    /**
     * @var bool|null
     */
    private $passive;

    /**
     * @var bool|null
     */
    private $ssl;

    /**
     * @var int|null
     */
    private $timeout;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_FTP;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return array_filter(
            [
                'host' => $this->host,
                'username' => $this->username,
                'password' => $this->password,

                'port' => $this->port,
                'root' => $this->root,
                'passive' => $this->passive,
                'ssl' => $this->ssl,
                'timeout' => $this->timeout,
            ],
            function ($value) {
                return null !== $value;
            }
        );
    }

    /**
     * @param string $host
     */
    protected function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @param string $username
     */
    protected function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    protected function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param int $port
     */
    protected function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @param string $root
     */
    protected function setRoot(string $root): void
    {
        $this->root = $root;
    }

    /**
     * @param bool|null $passive
     */
    protected function setPassive(?bool $passive): void
    {
        $this->passive = $passive;
    }

    /**
     * @param bool|null $ssl
     */
    protected function setSsl(?bool $ssl): void
    {
        $this->ssl = $ssl;
    }

    /**
     * @param int|null $timeout
     */
    protected function setTimeout(?int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params): void
    {
        $this->host = $params['host'];
        $this->username = $params['username'];
        $this->password = $params['password'];

        $this->port = $params['port'] ?? null;
        $this->root = $params['root'] ?? null;
        $this->passive = $params['passive'] ?? null;
        $this->ssl = $params['ssl'] ?? null;
        $this->timeout = $params['timeout'] ?? null;
    }
}
