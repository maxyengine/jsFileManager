<?php

namespace Nrg\I18n\Service;

use Nrg\Di\Abstraction\Injector;
use Nrg\I18n\Abstraction\Dictionary;
use Nrg\I18n\Abstraction\Translator;
use Nrg\I18n\Value\CompositeMessage;
use Nrg\I18n\Value\Message;
use ReflectionException;

/**
 * Class I18nTranslator.
 */
class I18nTranslator implements Translator
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var null|string
     */
    private $locale;

    /**
     * @var array
     */
    private $dictionaries = [];


    public function __construct(Injector $injector, string $locale = null)
    {
        $this->injector = $injector;
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($message): string
    {
        if (null === $this->locale) {
            return $message;
        }

        return $message instanceof Message ?
            $this->translateMessage($message) :
            $this->translateText($message);
    }

    /**
     * {@inheritdoc}
     */
    public function addDictionary(string $class): void
    {
        if (!$this->hasDictionary($class)) {
            $this->dictionaries[$class] = null;
        }
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function hasDictionary(string $class): bool
    {
        return array_key_exists($class, $this->dictionaries);
    }

    /**
     * @param string $class
     *
     * @return Dictionary
     *
     * @throws ReflectionException
     */
    private function getDictionary(string $class): Dictionary
    {
        if (null === $this->dictionaries[$class]) {
            $this->dictionaries[$class] = $this->injector->createObject($class);
        }

        return $this->dictionaries[$class];
    }

    /**
     * @param Message $message
     *
     * @return Message
     *
     * @throws ReflectionException
     */
    private function translateMessage(Message $message): Message
    {
        return $message instanceof CompositeMessage ?
            $this->translateCompositeMessage($message) :
            new Message($this->translateText($message->getText()), ...$message->getParams());
    }

    /**
     * @param CompositeMessage $message
     *
     * @return CompositeMessage
     *
     * @throws ReflectionException
     */
    private function translateCompositeMessage(CompositeMessage $message): CompositeMessage
    {
        $compositeMessage = new CompositeMessage();
        foreach ($message->getMessages() as $subMessage) {
            $compositeMessage->addMessage($this->translateMessage($subMessage));
        }

        return $compositeMessage;
    }

    /**
     * @param string $text
     *
     * @return string
     *
     * @throws ReflectionException
     */
    private function translateText(string $text): string
    {
        foreach ($this->dictionaries as $class => $dictionary) {
            $result = $this->getDictionary($class)->translate($text, $this->locale);
            if ($text !== $result) {
                return $result;
            }
        }

        return $text;
    }
}
