<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Escaper;
use Phalcon\Flash\Session;

class FlashSessionProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'flashSession';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $session = $this->application->getSession();

        $this->container->setShared(static::IDENTIFIER, function () use ($session) {

            $flash = new Session(new Escaper, $session);
            $flash->setCssClasses([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            return $flash;
        });
    }
}
