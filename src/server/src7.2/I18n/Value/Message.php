<?php

namespace Nrg\I18n\Value;

use JsonSerializable;

/**
 * Class Message.
 */
class Message implements JsonSerializable
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $params = [];

    /**
     * Message constructor.
     *
     * @param string $text
     * @param array  ...$params
     */
    public function __construct(string $text = '', ...$params)
    {
        $this->text = $text;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf($this->getText(), ...$this->getParams());
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return (string) $this;
    }
}
