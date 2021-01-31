<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Form\Forms\LoginForm;
use App\Model\Models\User;
use Exception;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;
use Phalcon\Http\ResponseInterface;

class AuthenticationController extends HtmlController
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
     * @throws DbException|InvalidArgumentException
     */
    public function indexAction(): ?ResponseInterface
    {
        $user = $this->authentication->getUser();
        if ($user instanceof User) {
            return $this->redirector->toLocation('special/page/1')->withNothing();
        }

        $this->view->setVar('form', new LoginForm);

        return null;
    }

    /**
     * Process the authentication login form.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function loginAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->toLocation('authentication')->withError("Invalid Request Type");
        }

        if ($this->authentication->viaForm(new LoginForm)) {
            return $this->redirector->toLocation('')->withSuccess("Credentials Authenticated");
        }

        return $this->redirector->toLocation('authentication')->withError("Invalid Credentials");
    }

    /**
     * Process the authentication logout request.
     *
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function logoutAction(): ResponseInterface
    {
        $this->authentication->remove();

        return $this->redirector->toLocation('authentication')->withSuccess("Credentials Cleared");
    }
}
