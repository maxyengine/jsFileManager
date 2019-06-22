<?php

namespace Nrg\Utility\Abstraction;

use JsonSerializable;

/**
 * Interface Config.
 */
interface Config extends JsonSerializable
{
    public const MODE_KEY_NAME = 'mode';
    public const DEVELOPMENT_MODE = 'development';
    public const PRODUCTION_MODE = 'production';

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param null $defaultValue
     *
     * @return mixed
     */
    public function get(string $key, $defaultValue = null);

    /**
     * @param string $key
     * @param $value
     *
     * @return Config
     */
    public function set(string $key, $value): Config;

    /**
     * @param array $data
     *
     * @return Config
     */
    public function load(array $data): Config;

    /**
     * @return array
     */
    public function asArray(): array;

    /**
     * @return array
     */
    public function getPublic(): array;

    /**
     * @return string
     */
    public function getMode(): string;

    /**
     * @return bool
     */
    public function isDevelopmentMode(): bool;

    /**
     * @return bool
     */
    public function isProductionMode(): bool;
}
