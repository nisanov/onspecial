<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Form\Forms\RegistrationForm;
use App\Model\Models\User;
use Carbon\Carbon;
use Exception;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;
use Phalcon\Http\ResponseInterface;
use Phalcon\Security\Exception as SecurityException;

class RegistrationController extends HtmlController
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
     * Show the registration form.
     *
     * @return void
     */
    public function indexAction(): void
    {
        $this->view->setVar('form', new RegistrationForm(new User));
    }

    /**
     * Process the registration form.
     *
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws DbException
     * @throws SecurityException
     * @throws Exception
     */
    public function registerAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->toLocation('registration')->withError("Invalid Request Type");
        }

        $user = new User;
        $form = new RegistrationForm($user);
        if ($form->isValid($this->request->getPost())) {

            $user->password = $this->security->hash($user->password);
            $user->save();

            $token = $this->security->getRandom()->uuid();

            $this->cache->set($token, $user->id, 60 * 60 * 5); // token expires in 5 hours

            $data = $user->toArray();
            $data['link'] = $this->url->get("registration/activate/$token");

            if ($this->mailer->sendTo([$data['email'] => $data['name']], 'registration', ['data' => $data])) {
                return $this->redirector->toLocation('authentication')->withSuccess("Activation Instructions Sent");
            } else {
                return $this->redirector->toLocation('registration')->withError("Failed to Send Activation Instructions");
            }
        }

        return $this->redirector->toLocation('registration')->withSuccess("Invalid Registration");
    }

    /**
     * Process the registration token activation request.
     *
     * @param string $token
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws DbException
     */
    public function activateAction(string $token): ResponseInterface
    {
        $userId = $this->cache->get($token);
        if ($userId === null) {
            return $this->redirector->toLocation('registration')->withError("Expired or Missing Token");
        }

        $this->cache->delete($token);

        $user = User::findFirst($userId);
        if (!$user instanceof User) {
            return $this->redirector->toLocation('registration')->withError("Expired or Missing User");
        }

        $message = "Already Activated";
        if ($user->email_verified_at === null) {
            $user->email_verified_at = Carbon::now()->toDateTimeString('microseconds');
            $user->save();
            $message = "Account Activated";
        }

        $this->authentication->authenticateUser($user);

        return $this->redirector->toLocation('')->withSuccess($message);
    }
}
