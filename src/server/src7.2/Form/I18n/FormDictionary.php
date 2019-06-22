<?php

namespace Nrg\Form\I18n;

use Nrg\I18n\Service\I18nDictionary;

/**
 * Class FormDictionary.
 */
class FormDictionary extends I18nDictionary
{
    protected function getI18nPath(): string
    {
        return __DIR__.'/../../../i18n/form';
    }
}
