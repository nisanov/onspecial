<?php

declare(strict_types=1);

namespace App\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

/**
 * @property \Phalcon\Logger $logger
 * @property \App\Service\Services\Redirector $redirector
 * @property \App\Service\Services\Authentication $authentication
 * @property \App\Service\Services\Mailer $mailer
 * @property \Phalcon\Cache $cache
 * @property \App\Service\Services\Toggle $toggle
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
