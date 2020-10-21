<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use App\Service\Services\Authentication;

class AuthenticationProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'authentication';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->setShared(static::IDENTIFIER, Authentication::class);
    }
}
