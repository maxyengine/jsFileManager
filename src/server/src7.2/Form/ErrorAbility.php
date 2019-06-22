<?php

namespace Nrg\Form;

use Nrg\I18n\Value\Message;

/**
 * Trait ErrorAbility.
 */
trait ErrorAbility
{
    /**
     * @var Message
     */
    private $errorMessage;

    /**
     * @var string[]
     */
    private $errorTexts = [
        'please provide a correct value',
    ];

    /**
     * @param Message $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage(Message $errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return Message
     */
    public function getErrorMessage(): Message
    {
        if (null === $this->errorMessage) {
            $this->setErrorMessage(new Message($this->errorTexts[0]));
        }

        return $this->errorMessage;
    }

    /**
     * @param string $text
     * @param int    $case
     *
     * @return $this
     */
    public function adjustErrorText(string $text, int $case = 0)
    {
        $this->errorTexts[$case] = $text;

        return $this;
    }

    /**
     * @param int   $case
     * @param array ...$params
     */
    protected function setErrorCase(int $case, ...$params): void
    {
        $this->setErrorMessage(new Message($this->errorTexts[$case], ...$params));
    }
}
