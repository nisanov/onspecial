<?php

declare(strict_types=1);

namespace App\Controller\Traits;

use App\Controller\JsonController;
use App\Service\Services\Authentication;
use App\Service\Services\Redirector;

/**
 * @property Authentication $authentication
 * @property Redirector $redirector
 */
trait RequiresAuthentication
{
    /**
     * Execute before every found action.
     * 
     * @return bool
     */
    public function beforeExecuteRoute(): bool
    {
        if ($this->authentication->getUser() === null) {
            if ($this instanceof JsonController) {
                $this->view->disable();
                $this->response->setContentType('application/json', "UTF-8");
                $this->response->setHeader('Cache-Control', "no-store");
                $this->response->setStatusCode(401);
                $this->response->setJsonContent(['message' => "Authentication Required"]);
                $this->response->send();
            } else {
                $this->redirector->withNothing('authentication');
            }

            return false;
        }

        return true;
    }
}
