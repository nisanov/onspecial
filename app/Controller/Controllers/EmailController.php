<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Model\Models\User;
use Phalcon\Mvc\View;
use Phalcon\Security\Exception;

class EmailController extends HtmlController
{
    /**
     * Initialize all controller actions.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
    }

    /**
     * Handle the before execute route event.
     *
     * @return bool
     */
    public function beforeExecuteRoute(): bool
    {
        if ($this->application->isProduction()) {

            $this->redirector->toLocation('special/page/1')->withError("The Email Controller is only accessible during development");

            return false;
        }

        return true;
    }

    /**
     * Show the registration email.
     *
     * @return void
     * @throws Exception
     */
    public function registrationAction(): void
    {
        $data = User::findFirstByEmail('yasha.nisanov@gmail.com')->toArray();

        $token = $this->security->getRandom()->uuid();

        $data['link'] = $this->url->get("registration/activate/$token");

        $this->view->setVar('data', $data);
    }

    /**
     * Show the registration email.
     *
     * @return void
     * @throws Exception
     */
    public function recoveryAction(): void
    {
        $data = User::findFirstByEmail('yasha.nisanov@gmail.com')->toArray();

        $token = $this->security->getRandom()->uuid();

        $data['link'] = $this->url->get("recovery/activate/$token");

        $this->view->setVar('data', $data);
    }
}
