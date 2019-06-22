<?php

namespace Nrg\I18n\Service;

use Nrg\I18n\Abstraction\Dictionary;
use Nrg\I18n\Persistence\Abstraction\TextRepository;
use Nrg\I18n\Persistence\FileDb\FileDbTextRepository;

/**
 * Class I18nDictionary.
 */
class I18nDictionary implements Dictionary
{
    /**
     * @var TextRepository
     */
    private $repository;

    /**
     * I18nDictionary constructor.
     *
     * @param null|TextRepository $repository
     */
    public function __construct(TextRepository $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function translate(string $text, string $locale): string
    {
        return $this->getRepository()->findByKey($text, $locale) ?? $text;
    }

    protected function getI18nPath(): string
    {
        return __DIR__.'/../../I18n';
    }

    /**
     * @return TextRepository
     */
    private function getRepository(): TextRepository
    {
        if (null === $this->repository) {
            $this->repository = new FileDbTextRepository($this->getI18nPath());
        }

        return $this->repository;
    }
}
