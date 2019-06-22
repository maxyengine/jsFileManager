<?php

namespace Nrg\Utility\Service;

use Nrg\Utility\Abstraction\Settings;
use RuntimeException;

/**
 * Class LocalSettings.
 *
 * Array settings implementation.
 */
class LocalSettings implements Settings
{
    private const SETTINGS_FILE_NAME = 'settings.php';

    /**
     * @var array
     */
    private $raw = [];

    /**
     * @param string $resourcesPath
     */
    public function __construct(string $resourcesPath)
    {
        $this->raw = self::createRaw($resourcesPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getServices(): array
    {
        return $this->raw[self::KEY_SERVICES] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents(): array
    {
        return $this->raw[self::KEY_EVENTS] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes(): array
    {
        return $this->raw[self::KEY_ROUTES] ?? [];
    }

    /**
     * @param string $path
     * @param array $raw
     *
     * @return array
     */
    private static function createRaw(string $path, array &$raw = []): array
    {
        foreach (self::scanDirectory($path) as $fileName) {
            $filePath = realpath($path.DIRECTORY_SEPARATOR.$fileName);

            if (is_dir($filePath)) {
                $settingsFileName = $filePath.DIRECTORY_SEPARATOR.self::SETTINGS_FILE_NAME;

                if (file_exists($settingsFileName)) {
                    $settings = require $settingsFileName;

                    foreach ($settings as $key => $array) {
                        $raw[$key] = array_merge($raw[$key] ?? [], $array);
                    }
                }
            }
        }

        return $raw;
    }

    /**
     * Scans directory.
     *
     * @param string $path
     *
     * @return array
     *
     * @throws RuntimeException
     */
    private static function scanDirectory(string $path): array
    {
        $contents = scandir($path);

        if (false === $contents) {
            throw new RuntimeException(sprintf('Cannot read directory \'%s\'', $path));
        }

        return array_diff($contents, ['..']);
    }
}
