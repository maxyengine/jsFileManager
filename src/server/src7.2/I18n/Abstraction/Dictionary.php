<?php

namespace Nrg\I18n\Abstraction;

interface Dictionary
{
    public function translate(string $text, string $locale): string;
}
