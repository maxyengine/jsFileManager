<?php

namespace Nrg\Uploader\Service;

use Nrg\Utility\Value\Size;

/**
 * Class DemoConfig
 */
class DemoConfig extends AppConfig
{
    private const DEMO_UPLOADS_FOLDER = '../demo-uploads';
    private const SESSION_NAME = 'nrg-uploader';

    public function __construct(string $path, array $publicKeys = [])
    {
        parent::__construct($path, $publicKeys);

        $this->load(
            [
                'mode' => 'production',
                'authorization' => true,
                'directFileAccess' => false,
                'maxSize' => Size::fromHumanString('100KB'),
            ]
        );
    }

    protected function resolveUploadsFolder(array &$data): AppConfig
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_NAME])) {
            $demoUploads = $this->createDirectory([realpath(dirname($this->getPath())), self::DEMO_UPLOADS_FOLDER]);
            $dailyDirectory = $this->createDirectory([$demoUploads, date('Y-m-d')]);

            $_SESSION[self::SESSION_NAME] = [
                'uploadsFolder' => $this->createDirectory([$dailyDirectory, round(microtime(true) * 1000)]),
            ];
        }

        $data['uploadsFolder'] = $_SESSION[self::SESSION_NAME]['uploadsFolder'];

        return $this;
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    private function createDirectory(array $parts): string
    {
        $directory = implode(DIRECTORY_SEPARATOR, $parts);

        if (!is_dir($directory)) {
            mkdir($directory);
        }

        return realpath($directory);
    }
}
