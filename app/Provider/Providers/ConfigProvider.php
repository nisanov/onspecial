<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Exception;
use Phalcon\Config;

class ConfigProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'config';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $path = $this->application->getBasePath('/app_config/config.php');

        $this->container->setShared(static::IDENTIFIER, function () use ($path) {

            if (!file_exists($path) || !is_readable($path)) {
                throw new Exception("Missing or unreadable configuration file at path: " . $path);
            }

            /** @noinspection PhpIncludeInspection */
            $configuration = require $path;

            return new Config($configuration);
        });
    }
}
