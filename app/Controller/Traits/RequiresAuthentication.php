<?php

declare(strict_types=1);

namespace App\Controller\Traits;

use App\Controller\JsonController;
use App\Service\Services\Authentication;
use App\Service\Services\Redirector;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;

/**
 * @property Authentication $authentication
 * @property Redirector $redirector
 */
trait RequiresAuthentication
{
    /**
     * Handles the before execute route event.
     *
     * @return bool
     * @throws DbException|InvalidArgumentException
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
                $this->redirector->toLocation('authentication')->withNothing();
            }

            return false;
        }

        return true;
    }
}
