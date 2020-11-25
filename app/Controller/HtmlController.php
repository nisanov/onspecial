<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Services\Authentication;
use App\Service\Services\Mailer;
use App\Service\Services\Redirector;
use Phalcon\Cache;
use Phalcon\Logger;
use Phalcon\Mvc\Controller;

/**
 * @property Logger $logger
 * @property Redirector $redirector
 * @property Authentication $authentication
 * @property Mailer $mailer
 * @property Cache $cache
 */
abstract class HtmlController extends Controller
{
}
