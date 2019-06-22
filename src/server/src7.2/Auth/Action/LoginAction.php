<?php

namespace Nrg\Auth\Action;

use Nrg\Auth\Dto\LoginInput;
use Nrg\Auth\Form\LoginForm;
use Nrg\Auth\UseCase\Login;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Exception\ValidationException;
use Nrg\Http\Value\HttpStatus;
use Exception;

/**
 * Class LoginAction
 */
class LoginAction
{
    /**
     * @var LoginForm
     */
    private $form;

    /**
     * @var Login
     */
    private $login;

    /**
     * @param LoginForm $form
     * @param Login $login
     */
    public function __construct(LoginForm $form, Login $login)
    {
        $this->form = $form;
        $this->login = $login;
    }

    /**
     * @param HttpExchangeEvent $event
     *
     * @throws Exception
     */
    public function onNext(HttpExchangeEvent $event)
    {
        $this->form->populate($event->getRequest()->getBodyParams());

        if ($this->form->hasErrors()) {
            throw new ValidationException($this->form->getErrors());
        }

        $event->getResponse()
            ->setStatusCode(HttpStatus::CREATED)
            ->setBody($this->login->execute(new LoginInput($this->form->getValues())));
    }
}
