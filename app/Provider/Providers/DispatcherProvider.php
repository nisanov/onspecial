<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Cli\Dispatcher as CliDispatcher;

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
        $isCommandLine = $this->application->isCommandLine();

        $this->container->setShared(static::IDENTIFIER, function () use ($isCommandLine) {

            if ($isCommandLine) {
                $dispatcher = new CliDispatcher;
                $dispatcher->setDefaultNamespace('App\Task\Tasks');
            } else {
                $dispatcher = new MvcDispatcher;
                $dispatcher->setDefaultNamespace('App\Controller\Controllers');
            }

            return $dispatcher;
        });
    }
}
