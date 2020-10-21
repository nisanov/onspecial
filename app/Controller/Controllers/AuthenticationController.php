<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\WebController;
use App\Form\Forms\LoginForm;
use App\Model\Models\User;
use Phalcon\Http\ResponseInterface;

class AuthenticationController extends WebController
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
     * Show the authentication login form.
     *
     * @return ResponseInterface|null
     */
    public function indexAction(): ?ResponseInterface
    {
        $user = $this->authentication->getUser();
        if ($user instanceof User) {
            return $this->redirector->withSuccess('', "Already Authenticated");
        }

        if ($this->authentication->hasRememberMeToken()) {
            if ($this->authentication->viaRememberMeToken()) {
                return $this->redirector->withSuccess('', "Remembered Credentials");
            }
        }

        $this->view->setVar('form', new LoginForm);

        return null;
    }

    /**
     * Process the authentication login form.
     *
     * @return ResponseInterface
     */
    public function loginAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->withError('authentication', "Invalid Request Type");
        }

        if ($this->authentication->viaForm(new LoginForm)) {
            return $this->redirector->withSuccess('', "Credentials Authenticated");
        }

        return $this->redirector->withError('authentication', "Invalid Credentials");
    }

    /**
     * Process the authentication logout request.
     *
     * @return ResponseInterface
     */
    public function logoutAction(): ResponseInterface
    {
        $this->authentication->remove();

        return $this->redirector->withSuccess('authentication', "Credentials Cleared");
    }
}
