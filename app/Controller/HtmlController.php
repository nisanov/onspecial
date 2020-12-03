<?php

declare(strict_types=1);

namespace App\Controller;

use Phalcon\Mvc\Controller;

/**
 * @property \App\Application $application
 * @property \Phalcon\Logger $logger
 * @property \App\Service\Services\Redirector $redirector
 * @property \App\Service\Services\Authentication $authentication
 * @property \App\Service\Services\Mailer $mailer
 * @property \Phalcon\Cache $cache
 * @property \App\Service\Services\Toggle $toggle
 */
abstract class HtmlController extends Controller
{
    /**
     * Handle the after excecute route event.
     *
     * @return void
     */
    public function afterExecuteRoute(): void
    {
        $this->session->set('requested', $this->application->getRequest());
    }
}
