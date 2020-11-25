<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Form\Forms\RecoveryForm;
use App\Model\Models\User;
use Carbon\Carbon;
use Phalcon\Http\ResponseInterface;

class RecoveryController extends HtmlController
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
     * Show the recovery form.
     *
     * @return void
     */
    public function indexAction(): void
    {
        $this->view->setVar('form', new RecoveryForm);
    }

    /**
     * Process the recovery form.
     *
     * @return ResponseInterface
     */
    public function recoverAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->withError('recovery', "Invalid Request Type");
        }

        $data = $this->request->getPost();

        if ((new RecoveryForm)->isValid($data)) {

            $user = User::findFirstByEmail($data['email']);

            $token = $this->security->getRandom()->uuid();

            $this->cache->set($token, $user->getId(), 60 * 60 * 5); // token expires in 5 hours

            $data = $user->toArray();
            $data['link'] = $this->url->get("recovery/activate/$token");

            if ($this->mailer->sendTo([$data['email'] => $data['name']], 'recovery', ['data' => $data])) {
                return $this->redirector->withSuccess('authentication', "Recovery Instructions Sent");
            } else {
                return $this->redirector->withError('authentication', "Activation Instructions Sent");
            }
        }

        return $this->redirector->withSuccess('recovery', "Invalid Recovery Information");
    }

    /**
     * Process the recovery token activation request.
     *
     * @param string $token
     * @return ResponseInterface
     */
    public function activateAction(string $token): ResponseInterface
    {
        $userId = $this->cache->get($token);
        if ($userId === null) {
            return $this->redirector->withError('recovery', "Expired or Missing Token");
        }

        $this->cache->delete($token);

        $user = User::findFirst($userId);
        if (!$user instanceof User) {
            return $this->redirector->withError('recovery', "Expired or Missing User");
        }

        $message = "Recovery Token Verified";
        if ($user->getEmailVerifiedAt() === null) {
            $user->setEmailVerifiedAt(Carbon::now());
            $user->save();
            $message .= " - Account Activated";
        }

        $this->authentication->authenticateUser($user);

        return $this->redirector->withSuccess('profile', $message);
    }
}
