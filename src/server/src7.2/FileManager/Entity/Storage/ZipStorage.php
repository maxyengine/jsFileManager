<?php

namespace Nrg\FileManager\Entity\Storage;

use Nrg\FileManager\Entity\Storage;

/**
 * Class ZipStorage.
 *
 * ZipStorage entity implementation.
 */
class ZipStorage extends Storage
{
    /**
     * @var string
     */
    private $location;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_ZIP;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return [
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    protected function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params): void
    {
        $this->location = $params['location'];
    }
}
