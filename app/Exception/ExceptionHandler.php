<?php

declare(strict_types=1);

namespace App\Exception;

use App\Controller\JsonController;
use Exception;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class ExceptionHandler extends Injectable
{
    /**
     * Handle the dispatcher before exception event.
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param Exception $exception
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception): bool
    {
        if ($exception instanceof Generic && $dispatcher->getActiveController() instanceof JsonController) {

            $this->view->disable();
            $this->response->setContentType('application/json', "UTF-8");
            $this->response->setHeader('Cache-Control', "no-store");
            $this->response->setStatusCode($exception->getStatusCode());
            $this->response->setJsonContent(['message' => $exception->getMessage()]);
            $this->response->send();

            return false;
        }

        if ($exception instanceof DispatchException) {

            $dispatcher->forward([
                'controller' => 'error',
                'action' => 'pageNotFound',
            ]);

            return false;
        }

        return true;
    }
}
