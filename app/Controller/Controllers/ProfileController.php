<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\WebController;
use App\Form\Forms\ProfileForm;
use App\Model\Models\User;
use Carbon\Carbon;
use Phalcon\Http\ResponseInterface;

class ProfileController extends WebController
{
    /**
     * Initialize this controller.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->view->setTemplateAfter('common');
    }

    /**
     * Show the profile form.
     *
     * @return ResponseInterface|null
     */
    public function indexAction(): ?ResponseInterface
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirector->withError('authentication', "Unauthenticated Request");
        }

        $this->view->setVar('form', new ProfileForm($user));

        return null;
    }

    /**
     * Process the profile form.
     *
     * @return ResponseInterface
     */
    public function updateAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->withError('profile', "Invalid Request Type");
        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirector->withError('authentication', "Unauthenticated Request");
        }

        $password = $user->getPassword();

        $form = new ProfileForm($user);
        if ($form->isValid($this->request->getPost())) {

            if ($form->getValue('password') !== "") {
                $password = $this->security->hash($form->getValue('password'));
            }

            $user->setPassword($password);

            if (!empty($user->getChangedFields())) {
                $user->save();
            }

            return !empty($user->getUpdatedFields())
                ? $this->redirector->withSuccess('profile', "Updated Profile")
                : $this->redirector->withSuccess('profile', "Unchanged Profile");
        }

        return $this->redirector->withSuccess('profile', "Invalid Profile");
    }
}
