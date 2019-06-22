<?php

namespace Nrg\Form\Abstraction;

use Nrg\Form\Element;
use Nrg\I18n\Value\Message;

/**
 * Interface Validator.
 */
interface Validator
{
    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool;

    /**
     * @return Message
     */
    public function getErrorMessage(): Message;

    /**
     * @param Message $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage(Message $errorMessage);

    /**
     * @param string $text
     * @param int    $case
     *
     * @return $this
     */
    public function adjustErrorText(string $text, int $case = 0);
}
