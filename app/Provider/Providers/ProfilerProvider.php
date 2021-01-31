<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Db\Profiler;

class ProfilerProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'profiler';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->setShared(static::IDENTIFIER, function () {

            return new Profiler;
        });
    }
}
