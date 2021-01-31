<?php

declare(strict_types=1);

namespace App\Service;

use Phalcon\Cache;
use Phalcon\Di\Injectable;

/**
 * @property-read Cache $cache
 */
abstract class Generic extends Injectable
{
}
