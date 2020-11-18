<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Formatter\Formatters\Line;
use App\Provider\Generic;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class LoggerProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'logger';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->setShared(static::IDENTIFIER, function () {

            $adapter = new Stream('php://stdout');
            $adapter->setFormatter(new Line);

            $logger = new Logger('messages');
            $logger->setAdapters(['main' => $adapter]);

            return $logger;
        });
    }
}
