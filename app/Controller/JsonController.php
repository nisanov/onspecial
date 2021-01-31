<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Services\Authentication;
use App\Service\Services\Mailer;
use App\Service\Services\Redirector;
use App\Service\Services\Toggle;
use Phalcon\Cache;
use Phalcon\Logger;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

/**
 * @property-read Logger $logger
 * @property-read Redirector $redirector
 * @property-read Authentication $authentication
 * @property-read Mailer $mailer
 * @property-read Cache $cache
 * @property-read Toggle $toggle
 */
abstract class JsonController extends Controller
{
    /**
     * Execute after every found action.
     *
     * @param Dispatcher $dispatcher
     * @return void
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
