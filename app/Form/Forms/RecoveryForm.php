<?php

declare(strict_types=1);

namespace App\Form\Forms;

use App\Form\Elements\Button;
use App\Form\Elements\Input;
use App\Form\Generic as GenericForm;
use App\Model\Models\User;
use App\Validator\Validators\Existence;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class RecoveryForm extends GenericForm
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
        $email->setFilters(['trim', 'string']);
        $email->setUserOption('message', $this->getSessionStoredMessage('email'));
        $email->addValidator(new PresenceOf(['message' => "The email must be specified"]));
        $email->addValidator(new Email(['message' => "The email must be a valid format"]));
        $email->addValidator(new Existence(['message' => "The email must exist on record", 'model' => new User]));

        $this->add($email);
        $this->add(new Button('Recover'));
    }
}
