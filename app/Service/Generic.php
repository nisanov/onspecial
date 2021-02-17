<?php

declare(strict_types=1);

namespace App\Service;

use Phalcon\Cache;
use Phalcon\Di\Injectable;
use Phalcon\Logger;

/**a
 * @property-read Logger $logger
 * @property-read Cache $cache
 */
abstract class Generic extends Injectable
{
}
