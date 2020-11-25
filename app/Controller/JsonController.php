<?php

declare(strict_types=1);

namespace App\Controller;

use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

/**
 * @property Logger $logger
 * @property Redirector $redirector
 * @property Authentication $authentication
 * @property Mailer $mailer
 * @property Cache $cache
 */
abstract class JsonController extends Controller
{
    /**
     * Execute after every found action.
     * 
     * @param Dispatcher $dispatcher
     * @return ResponseInterface|null
     */
    public function afterExecuteRoute(Dispatcher $dispatcher): void
    {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setHeader('Cache-Control', 'no-store');

        $data = $dispatcher->getReturnedValue();
        $dispatcher->setReturnedValue([]);

        if ($this->response->isSent() !== true) {
            $this->response->setJsonContent($data);
            $this->response->send();
        }
    }
}
