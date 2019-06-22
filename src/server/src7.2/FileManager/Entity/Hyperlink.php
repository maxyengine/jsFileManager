<?php

namespace Nrg\FileManager\Entity;

use Nrg\Http\Value\Url;

/**
 * Class Hyperlink.
 *
 * Hyperlink entity implementation.
 */
class Hyperlink extends File
{
    /**
     * @var Url|null
     */
    private $url;

    /**
     * @return string
     */
    public function getType(): string
    {
        return File::TYPE_HYPERLINK;
    }

    /**
     * @param Url $url
     *
     * @return Hyperlink
     */
    public function setUrl(Url $url): Hyperlink
    {
        $this->url = $url;
        $this->setContents((string)$url);

        return $this;
    }

    public function setContents(string $contents): File
    {
        $this->url = new Url($contents);
        parent::setContents((string)$this->url);

        return $this;
    }

    /**
     * @return Url|null
     */
    public function getUrl(): ?Url
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return parent::jsonSerialize() + ['url' => (string)$this->getUrl()];
    }
}
