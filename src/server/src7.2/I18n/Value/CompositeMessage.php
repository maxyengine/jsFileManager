<?php

namespace Nrg\I18n\Value;

/**
 * Class CompositeMessage.
 */
class CompositeMessage extends Message
{
    /**
     * @var Message[]
     */
    private $messages;

    /**
     * @var string
     */
    private $separator = ', ';

    /**
     * CompositeMessage constructor.
     *
     * @param Message[] ...$messages
     */
    public function __construct(Message ...$messages)
    {
        parent::__construct();
        $this->messages = $messages;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     *
     * @return CompositeMessage
     */
    public function addMessage(Message $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @param string $separator
     *
     * @return CompositeMessage
     */
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getText(): string
    {
        $texts = [];
        foreach ($this->getMessages() as $message) {
            $texts[] = $message->getText();
        }

        return implode($this->separator, $texts);
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        $params = [];
        foreach ($this->getMessages() as $message) {
            $params[] = $message->getParams();
        }

        return array_merge(...$params);
    }
}
