<?php

namespace Nrg\FileManager\Entity\Storage;

use Nrg\FileManager\Entity\Storage;

/**
 * Class SftpStorage.
 *
 * SftpStorage entity implementation.
 */
class SftpStorage extends Storage
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string|null
     */
    private $root;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $privateKey;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var int|null
     */
    private $timeout;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_SFTP;
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
                'privateKey' => $this->privateKey,

                'port' => $this->port,
                'root' => $this->root,
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
     * @param string $privateKey
     */
    protected function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
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

        $this->password = $params['password'] ?? null;
        $this->privateKey = $params['privateKey'] ?? null;

        $this->port = $params['port'] ?? null;
        $this->root = $params['root'] ?? null;
        $this->timeout = $params['timeout'] ?? null;
    }
}
