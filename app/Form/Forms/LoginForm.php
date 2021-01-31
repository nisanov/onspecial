<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\ButtonElement;
use App\Form\Elements\CheckboxElement;
use App\Form\Elements\InputElement;
use App\Form\GenericForm;
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
        $email = new InputElement('email', ['type' => 'email', 'value' => $this->getSessionStoredValue('email')]);
        $email->setLabel('Email');
        $email->setFilters(['trim', 'string']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));

        $password = new InputElement('password', ['type' => 'password', 'value' => $this->getSessionStoredValue('password')]);
        $password->setLabel('Password');
        $password->setFilters(['trim', 'string']);
        $password->setUserOption('message', $this->getSessionStoredMessage('password'));
        $password->addValidator(new PresenceOf(['message' => "The password must be specified"]));
        $password->addValidator(new StringLength(['message' => "The password is too short; min(8)", 'min' => 8]));

        $checkbox = new CheckboxElement('remember', ['checked' => $this->getSessionStoredValue('remember') === 'yes' ? true : null]);
        $checkbox->setLabel('Remember Me');

        $this->add($email);
        $this->add($password);
        $this->add($checkbox);
        $this->add(new ButtonElement('Login'));
    }
}
