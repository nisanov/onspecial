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
            return $this->redirector->toLocation('recovery')->withError("Invalid Request Type");
        }

        $data = $this->request->getPost();

        if ((new RecoveryForm)->isValid($data)) {

            $user = User::findFirstByEmail($data['email']);

            $token = $this->security->getRandom()->uuid();

            $this->cache->set($token, $user->id, 60 * 60 * 5); // token expires in 5 hours

            $data = $user->toArray();
            $data['link'] = $this->url->get("recovery/activate/$token");

            if ($this->mailer->sendTo([$data['email'] => $data['name']], 'recovery', ['data' => $data])) {
                return $this->redirector->toLocation('authentication')->withSuccess("Recovery Instructions Sent");
            } else {
                return $this->redirector->toLocation('authentication')->withError("Activation Instructions Sent");
            }
        }

        return $this->redirector->toLocation('recovery')->withSuccess("Invalid Recovery Information");
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
            return $this->redirector->toLocation('recovery')->withError("Expired or Missing Token");
        }

        $this->cache->delete($token);

        $user = User::findFirst($userId);
        if (!$user instanceof User) {
            return $this->redirector->toLocation('recovery')->withError("Expired or Missing User");
        }

        $message = "Recovery Token Verified";
        if ($user->email_verified_at === null) {
            $user->email_verified_at = Carbon::now()->toDateTimeString('microseconds');
            $user->save();
            $message .= " - Account Activated";
        }

        $this->authentication->authenticateUser($user);

        return $this->redirector->toLocation('profile')->withSuccess($message);
    }
}
