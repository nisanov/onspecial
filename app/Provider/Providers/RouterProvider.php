<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Exception;
use Phalcon\Cli\Router as CliRouter;
use Phalcon\Mvc\Router as MvcRouter;

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
        $isCommandLine = $this->application->isCommandLine();

        $this->container->setShared(static::IDENTIFIER, function () use ($basePath, $isCommandLine) {

            $routes = $basePath . '/app_config/routes.php';
            if (!file_exists($routes) || !is_readable($routes)) {
                throw new Exception("Missing or unreadable routes file at path: " . $routes);
            }

            if ($isCommandLine) {
                $router = new CliRouter;
            } else {
                $router = new MvcRouter;
                $router->removeExtraSlashes(true);

                /** @noinspection PhpIncludeInspection */
                require_once $routes;
            }

            return $router;
        });
    }
}
