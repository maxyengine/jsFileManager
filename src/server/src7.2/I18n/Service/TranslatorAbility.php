<?php

namespace Nrg\I18n\Service;

use Nrg\I18n\Abstraction\Translator;
use ReflectionException;

/**
 * Trait TranslatorAbility.
 */
trait TranslatorAbility
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param $message
     * @return string
     *
     * @throws ReflectionException
     */
    public function t($message): string
    {
        return null === $this->translator ? $message : $this->translator->translate($message);
    }

    /**
     * @param string $class
     */
    public function addDictionary(string $class): void
    {
        if (null !== $this->translator) {
            $this->translator->addDictionary($class);
        }
    }
}
