<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Http\Response\Cookies;

class CookiesProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'cookies';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $crypticKey = $this->application->getConfig()->path('application.crypticKey');

        $this->container->setShared(static::IDENTIFIER, function () use ($crypticKey) {

            $cookies = new Cookies;
            $cookies->setSignKey($crypticKey);

            return $cookies;
        });
    }
}
