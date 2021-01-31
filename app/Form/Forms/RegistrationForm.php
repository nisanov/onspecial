<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\ButtonElement;
use App\Form\Elements\CaptchaElement;
use App\Form\Elements\InputElement;
use App\Form\GenericForm;
use App\Model\Models\User;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Uniqueness;

class RegistrationForm extends GenericForm
{
    /**
     * Initialize this form.
     *
     * @param User $user
     * @return void
     */
    public function initialize(User $user): void
    {
        $name = new InputElement('name', ['type' => 'text', 'value' => $this->getSessionStoredValue('name', $user->name)]);
        $name->setLabel('Name');
        $name->setFilters(['trim', 'string']);
        $name->setUserOption('message', $this->getSessionStoredMessage('name'));
        $name->addValidator(new PresenceOf(['message' => "The name must be specified"]));

        $email = new InputElement('email', ['type' => 'email', 'value' => $this->getSessionStoredValue('email')]);
        $email->setLabel('Email');
        $email->setFilters(['trim', 'string']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));
        $email->addValidator(new Uniqueness(['message' => "The email must not have been registered", 'model' => new User]));

        $password = new InputElement('password', ['type' => 'password', 'value' => $this->getSessionStoredValue('password')]);
        $password->setLabel('Password');
        $password->setFilters(['trim', 'string']);
        $password->setUserOption('message', $this->getSessionStoredMessage('password'));
        $password->addValidator(new PresenceOf(['message' => "The password must be specified"]));
        $password->addValidator(new StringLength(['message' => "The password is too short; min(8)", 'min' => 8]));

        $passwordConfirm = new InputElement('password_confirm', ['type' => 'password', 'value' => $this->getSessionStoredValue('password_confirm')]);
        $passwordConfirm->setLabel('Confirm Password');
        $passwordConfirm->setFilters(['trim', 'string']);
        $passwordConfirm->setUserOption('message', $this->getSessionStoredMessage('password_confirm'));
        $passwordConfirm->addValidator(new PresenceOf(['message' => "The password confirmation must be specified"]));
        $passwordConfirm->addValidator(new Confirmation(['message' => "The confirmation must match the password", 'with' => 'password']));

        $captcha = new CaptchaElement('captcha', ['type' => 'text']);
        $captcha->setLabel('Captcha Value');
        $captcha->setFilters(['trim', 'string']);
        $captcha->setUserOption('message', $this->getSessionStoredMessage('captcha'));
        $captcha->addValidator(new PresenceOf(['message' => "The captcha value must be specified"]));
        $captcha->addValidator(new Callback([
            'callback' => fn(array $data) => $data['captcha'] === $this->session->get('captcha'),
            'message' => "The capture value must match the captcha image",
        ]));

        $this->add($name);
        $this->add($email);
        $this->add($password);
        $this->add($passwordConfirm);
        $this->add($captcha);
        $this->add(new ButtonElement('Register'));
    }
}
