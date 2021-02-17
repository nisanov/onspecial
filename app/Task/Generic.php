<?php

declare(strict_types=1);

namespace App\Task;

use App\Application;
use Phalcon\Cache;
use Phalcon\Cli\Task;
use Phalcon\Logger;

/**
 * @property-read Application $application
 * @property-read Logger $logger
 * @property-read Cache $cache
 */
class Generic extends Task
{
}
