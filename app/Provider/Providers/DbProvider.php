<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Exception;

class DbProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'db';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $config = $this->application->getConfig()->path('database')->toArray();
        $className = 'Phalcon\\Db\\Adapter\\Pdo\\' . $config['adapter'];
        if (!class_exists($className)) {
            throw new Exception("The following adapter does not have an associated DB class: " . $config['adapter']);
        }

        $params = [
            'host' => $config['host'],
            'username' => $config['username'],
            'password' => $config['password'],
            'dbname' => $config['dbname'],
            'charset' => $config['charset'],
            'collation' => $config['collation'],
        ];

        if ($config['adapter'] === 'Postgresql') {
            unset($params['charset']);
        }

        $this->container->setShared(static::IDENTIFIER, function () use ($className, $params) {

            return new $className($params);
        });
    }
}
