<?php

namespace Nrg\Auth\Form\Validator;

use Nrg\Auth\Dto\LoginInput;
use Nrg\Auth\UseCase\AreValidEmailAndPassword;
use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;

/**
 * Class ValidEmailAndPassword.
 */
class ValidEmailAndPassword extends AbstractValidator
{
    public const CASE_INVALID_EMAIL_OR_PASSWORD = 0;

    /**
     * @var AreValidEmailAndPassword
     */
    private $areValidEmailAndPassword;

    public function __construct(AreValidEmailAndPassword $areValidEmailAndPassword)
    {
        $this->areValidEmailAndPassword = $areValidEmailAndPassword;
        $this->adjustErrorText('invalid email or password', self::CASE_INVALID_EMAIL_OR_PASSWORD);
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        $email = $element->getForm()->getElement('email');

        if ($email->hasError()) {
            return true;
        }

        $this->setErrorCase(self::CASE_INVALID_EMAIL_OR_PASSWORD);

        return $this->areValidEmailAndPassword->execute(
            new LoginInput(
                [
                    'email' => $email->getValue(),
                    'password' => $element->getValue(),
                ]
            )
        );
    }
}
