<?php

declare(strict_types=1);

namespace App\Controller;

use Phalcon\Mvc\Controller;

/**
 * @property \Phalcon\Logger $logger
 * @property \App\Service\Services\Redirector $redirector
 * @property \App\Service\Services\Authentication $authentication
 * @property \App\Service\Services\Mailer $mailer
 * @property \Phalcon\Cache $cache
 * @property \App\Service\Services\Toggle $toggle
 */
abstract class HtmlController extends Controller
{
}
