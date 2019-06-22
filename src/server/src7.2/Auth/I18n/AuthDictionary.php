<?php

namespace Nrg\Auth\I18n;

use Nrg\I18n\Service\I18nDictionary;

/**
 * Class AuthDictionary.
 */
class AuthDictionary extends I18nDictionary
{
    protected function getI18nPath(): string
    {
        return __DIR__.'/../../i18n';
    }
}
