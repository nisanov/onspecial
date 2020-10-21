<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Mvc\View;

class ViewProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'view';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $container = $this->container;
        $viewsDir = $this->application->getConfig()->path('application.viewsDir');

        $this->container->setShared(static::IDENTIFIER, function () use ($container, $viewsDir) {

            $view = new View;
            $view->setDI($container);
            $view->setViewsDir($viewsDir);

            return $view;
        });
    }
}
