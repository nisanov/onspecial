<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Cache;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Storage\SerializerFactory;

class CacheProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'cache';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $config = $this->application->getConfig()->path('redis')->toArray();
        $params = [
            'host' => $config['host'],
            'port' => $config['port'],
            'auth' => $config['password'],
            'index' => '3',
        ];

        $this->container->setShared(static::IDENTIFIER, function () use ($params) {

            $redis = new Redis(new SerializerFactory, $params);

            return new Cache($redis);
        });
    }
}
