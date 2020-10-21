<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Session\Manager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

class SessionProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'session';

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
            'index' => '1',
        ];

        $this->container->setShared(static::IDENTIFIER, function () use ($params) {

            $session = new Manager();
            $session->setAdapter(new Redis(new AdapterFactory(new SerializerFactory), $params));
            $session->start();

            return $session;
        });
    }
}
