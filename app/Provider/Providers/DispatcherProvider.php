<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Mvc\Dispatcher;

class DispatcherProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'dispatcher';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->setShared(static::IDENTIFIER, function () {

            $dispatcher = new Dispatcher;
            $dispatcher->setDefaultNamespace('App\Controller\Controllers');

            return $dispatcher;
        });
    }
}
