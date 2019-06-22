<?php

namespace Nrg\I18n\Persistence\FileDb;

use Nrg\I18n\Persistence\Abstraction\TextRepository;
use Nrg\I18n\Persistence\Exception\I18nStorageNotFound;

/**
 * Class FileDbTextRepository.
 */
class FileDbTextRepository implements TextRepository
{
    private const FILE_EXTENSION = '.php';

    private const TEXTS_FOLDER = 'texts';

    /**
     * @var string
     */
    private $i18nPath;

    /**
     * @var array
     */
    private $texts = [];

    /**
     * FileDbTextRepository constructor.
     *
     * @param string $i18nPath
     */
    public function __construct(string $i18nPath)
    {
        $this->i18nPath = realpath($i18nPath);
        if (false === $this->i18nPath) {
            throw new I18nStorageNotFound('I18n path not found.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByKey(string $key, string $locale)
    {
        if (!isset($this->texts[$locale])) {
            $filePath = realpath($this->i18nPath.DIRECTORY_SEPARATOR.self::TEXTS_FOLDER.DIRECTORY_SEPARATOR.$locale.self::FILE_EXTENSION);
            if (false === $filePath) {
                return null;
            }
            $this->texts[$locale] = require $filePath;
        }

        return $this->texts[$locale][$key] ?? null;
    }
}
