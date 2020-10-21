<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Exception;
use Phalcon\Mvc\Router;

class RouterProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'router';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $basePath = $this->application->getBasePath();

        $this->container->setShared(static::IDENTIFIER, function () use ($basePath) {

            $routes = $basePath . '/app_config/routes.php';
            if (!file_exists($routes) || !is_readable($routes)) {
                throw new Exception("Missing or unreadable routes file at path: " . $routes);
            }

            $router = new Router;
            $router->removeExtraSlashes(true);

            require_once $routes;

            return $router;
        });
    }
}
