<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\Button;
use App\Form\Elements\Input;
use App\Form\Generic as GenericForm;
use App\Model\Models\User;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;
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
        $name->setFilters(['trim', 'string']);
        $name->setUserOption('message', $this->getSessionStoredMessage('name'));
        $name->addValidator(new PresenceOf(['message' => "The name must be specified"]));

        $email = new Input('email', ['type' => 'email', 'value' => $this->getSessionStoredValue('email', $user->getEmail())]);
        $email->setLabel('Email');
        $email->setFilters(['trim', 'string']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));

        $specialsPerPage = new Input('specials_per_page', ['type' => 'number', 'value' => $this->getSessionStoredValue('specials_per_page', (string)$user->getSpecialsPerPage())]);
        $specialsPerPage->setLabel(('Specials Per Page (between 250-2000)'));
        $specialsPerPage->setFilters(['trim', 'absint']);
        $specialsPerPage->setUserOption('message', $this->getSessionStoredMessage('specials_per_page'));
        $specialsPerPage->addValidator(new Between(['message' => "The specials per page must be between 250 and 2000", 'minimum' => 250, 'maximum' => 2000]));

        $password = new Input('password', ['type' => 'password', 'value' => $this->getSessionStoredValue('password')]);
        $password->setLabel('Password');
        $password->setFilters(['trim', 'string']);
        $password->setUserOption('message', $this->getSessionStoredMessage('password'));
        $password->addValidator(new Callback(['callback' => function (array $data) {
            if (!empty(trim($data['password']))) {
                return new StringLength(['message' => "The password is too short; min(8)", 'min' => 8]);
            }
            return true;
        }]));

        $passwordConfirm = new Input('password_confirm', ['type' => 'password', 'value' => $this->getSessionStoredValue('password_confirm')]);
        $passwordConfirm->setLabel('Confirm Password');
        $passwordConfirm->setFilters(['trim', 'string']);
        $passwordConfirm->setUserOption('message', $this->getSessionStoredMessage('password_confirm'));
        $passwordConfirm->addValidator(new Confirmation(['message' => "The confirmation must match the password", 'with' => 'password']));

        $this->add($name);
        $this->add($email);
        $this->add($specialsPerPage);
        $this->add($password);
        $this->add($passwordConfirm);
        $this->add(new Button('Update'));
    }
}
