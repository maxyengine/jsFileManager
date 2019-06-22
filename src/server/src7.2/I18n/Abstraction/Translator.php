<?php

namespace Nrg\I18n\Abstraction;

use Nrg\I18n\Value\Message;
use ReflectionException;

/**
 * Interface Translator.
 */
interface Translator
{
    /**
     * @param Message|string $message
     *
     * @return string
     *
     * @throws ReflectionException
     */
    public function translate($message): string;

    /**
     * @param string $class
     */
    public function addDictionary(string $class): void;
}
