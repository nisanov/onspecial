<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use App\Service\Services\Url;

class UrlProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'url';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $basePath = $this->application->getConfig()->path('application.baseUri');
        $manifestPath = $this->application->getBasePath() . '/manifest.json';

        $this->container->setShared(static::IDENTIFIER, function () use ($basePath, $manifestPath) {

            $url = new Url();
            $url->setBaseUri($basePath);
            $url->setManifest($manifestPath);

            return $url;
        });
    }
}
