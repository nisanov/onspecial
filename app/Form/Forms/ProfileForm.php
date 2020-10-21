<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\Button;
use App\Form\Elements\Input;
use App\Form\Generic as GenericForm;
use App\Model\Models\User;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ProfileForm extends GenericForm
{
    /**
     * Initialize this form.
     *
     * @param User $user
     * @return void
     */
    public function initialize(User $user): void
    {
        $name = new Input('name', ['type' => 'text', 'value' => $this->getSessionStoredValue('name', $user->getName())]);
        $name->setLabel('Name');
        $name->setFilters(['string', 'trim']);
        $name->setUserOption('message', $this->getSessionStoredMessage('name'));
        $name->addValidator(new PresenceOf(['message' => "The name must be specified"]));

        $email = new Input('email', ['type' => 'email', 'value' => $this->getSessionStoredValue('email', $user->getEmail())]);
        $email->setLabel('Email');
        $email->setFilters(['string', 'trim']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));

        $password = new Input('password', ['type' => 'password', 'value' => $this->getSessionStoredValue('password')]);
        $password->setLabel('Password');
        $password->setFilters(['string', 'trim']);
        $password->setUserOption('message', $this->getSessionStoredMessage('password'));
        $password->addValidator(new Callback(['callback' => function (array $data) {
            if (!empty(trim($data['password']))) {
                return new StringLength(['message' => "The password is too short; min(8)", 'min' => 8]);
            }
            return true;
        }]));

        $passwordConfirm = new Input('password_confirm', ['type' => 'password', 'value' => $this->getSessionStoredValue('password_confirm')]);
        $passwordConfirm->setLabel('Confirm Password');
        $passwordConfirm->setFilters(['string', 'trim']);
        $passwordConfirm->setUserOption('message', $this->getSessionStoredMessage('password_confirm'));
        $passwordConfirm->addValidator(new Confirmation(['message' => "The confirmation must match the password", 'with' => 'password']));

        $this->add($name);
        $this->add($email);
        $this->add($password);
        $this->add($passwordConfirm);
        $this->add(new Button('Update'));
    }
}
