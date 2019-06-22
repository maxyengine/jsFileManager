<?php

namespace Nrg\Uploader\Service;

use JsonSerializable;
use Nrg\Utility\Service\ArrayConfig;
use Nrg\Utility\Value\Size;
use RuntimeException;

/**
 * Class AppConfig
 */
class AppConfig extends ArrayConfig implements JsonSerializable
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @param array $publicKeys
     */
    public function __construct(string $path, array $publicKeys = [])
    {
        $this->path = $path;
        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid config file format.');
        }

        $this->resolve($data);

        parent::__construct($data, $publicKeys);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        if ($this->has('maxSize')) {
            $data['maxSize'] = $this->get('maxSize')->getValue();
        }

        return $data;
    }

    private function resolve(array &$data)
    {
        $this
            ->resolveUploadsFolder($data)
            ->resolveMaxSize($data);
    }

    protected function resolveUploadsFolder(array &$data): AppConfig
    {
        $basePath = dirname($this->path);

        if (!empty($data['uploadsFolder'])) {
            $data['uploadsFolder'] = realpath("{$basePath}/{$data['uploadsFolder']}");
        }

        return $this;
    }

    private function resolveMaxSize(array &$data): AppConfig
    {
        if (!empty($data['maxSize'])) {
            $data['maxSize'] = Size::fromHumanString($data['maxSize']);
        }

        return $this;
    }
}
