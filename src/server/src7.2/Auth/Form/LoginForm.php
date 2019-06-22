<?php

namespace Nrg\Auth\Form;

use Nrg\Auth\Form\Element\EmailElement;
use Nrg\Auth\Form\Element\PasswordElement;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class LoginForm
 */
class LoginForm extends Form
{
    public function __construct(Translator $translator, EmailElement $emailElement, PasswordElement $passwordElement)
    {
        parent::__construct($translator);

        $this
            ->addElement($emailElement)
            ->addElement($passwordElement);
    }
}
