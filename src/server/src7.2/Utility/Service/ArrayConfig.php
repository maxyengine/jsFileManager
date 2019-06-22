<?php

namespace Nrg\Utility\Service;

use Nrg\Utility\Abstraction\Config;

/**
 * Class ArrayConfig.
 *
 * Array configuration implementation.
 */
class ArrayConfig implements Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $publicKeys;

    /**
     * @param array $config
     * @param array $publicKeys
     */
    public function __construct(array $config, array $publicKeys = [])
    {
        $this->config = $config;
        $this->publicKeys = $publicKeys;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->config[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $defaultValue = null)
    {
        return $this->config[$key] ?? $defaultValue;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): Config
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $data): Config
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asArray(): array
    {
        return (array)$this->config;
    }

    /**
     * @return array
     */
    public function getPublic(): array
    {
        return array_intersect_key($this->asArray(), array_flip($this->publicKeys));
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getPublic();
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->get(self::MODE_KEY_NAME, self::PRODUCTION_MODE);
    }

    /**
     * @return bool
     */
    public function isDevelopmentMode(): bool
    {
        return $this->getMode() === self::DEVELOPMENT_MODE;
    }

    /**
     * @return bool
     */
    public function isProductionMode(): bool
    {
        return $this->getMode() === self::PRODUCTION_MODE;
    }
}
