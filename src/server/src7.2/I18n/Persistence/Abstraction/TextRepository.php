<?php

namespace Nrg\I18n\Persistence\Abstraction;

/**
 * Interface TextRepository.
 */
interface TextRepository
{
    /**
     * @param string $key
     * @param string $locale
     *
     * @return null|string
     */
    public function findByKey(string $key, string $locale);
}
