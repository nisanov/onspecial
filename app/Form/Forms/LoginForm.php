<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\Button;
use App\Form\Elements\Checkbox;
use App\Form\Elements\Input;
use App\Form\Generic as GenericForm;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class LoginForm extends GenericForm
{
    /**
     * Initialize this form.
     *
     * @return void
     */
    public function initialize(): void
    {
        $email = new Input('email', ['type' => 'email', 'value' => $this->getSessionStoredValue('email')]);
        $email->setLabel('Email');
        $email->setFilters(['string', 'trim']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));

        $password = new Input('password', ['type' => 'password', 'value' => $this->getSessionStoredValue('password')]);
        $password->setLabel('Password');
        $password->setFilters(['string', 'trim']);
        $password->setUserOption('message', $this->getSessionStoredMessage('password'));
        $password->addValidator(new PresenceOf(['message' => "The password must be specified"]));
        $password->addValidator(new StringLength(['message' => "The password is too short; min(8)", 'min' => 8]));

        $checkbox = new Checkbox('remember', ['checked' => $this->getSessionStoredValue('remember') === 'yes' ? true : null]);
        $checkbox->setLabel('Remember Me');

        $this->add($email);
        $this->add($password);
        $this->add($checkbox);
        $this->add(new Button('Login'));
    }
}
