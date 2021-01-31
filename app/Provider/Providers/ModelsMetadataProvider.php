<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Mvc\Model\MetaData\Redis;
use Phalcon\Storage\SerializerFactory;

class ModelsMetadataProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'modelsMetadata';

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
            'index' => '2',
        ];

        $this->container->setShared(static::IDENTIFIER, function () use ($params) {

            return new Redis(new AdapterFactory(new SerializerFactory), $params);
        });
    }
}
