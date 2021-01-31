<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application;
use App\Service\Services\Authentication;
use App\Service\Services\Mailer;
use App\Service\Services\Redirector;
use App\Service\Services\Toggle;
use Phalcon\Cache;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;
use Phalcon\Logger;
use Phalcon\Mvc\Controller;

/**
 * @property-read Application $application
 * @property-read Logger $logger
 * @property-read Redirector $redirector
 * @property-read Authentication $authentication
 * @property-read Mailer $mailer
 * @property-read Cache $cache
 * @property-read Toggle $toggle
 */
abstract class HtmlController extends Controller
{
    /**
     * Handle the after execute route event.
     *
     * @return void
     * @throws DbException|InvalidArgumentException
     */
    public function afterExecuteRoute(): void
    {
        $this->response->setHeader('X-Username', $this->authentication->hasUser() ? $this->authentication->getUser()->email : 'anonymous');
        $this->response->setHeader('X-Token', $this->application->getToken());

        $this->session->set('requested', $this->application->getRequest());
    }
}
