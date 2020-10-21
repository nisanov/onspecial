<?php

declare(strict_types=1);

namespace App\Provider;

use App\Application;
use Exception;
use Phalcon\Di\FactoryDefault;

abstract class Generic implements GenericInterface
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string|null
     */
    const IDENTIFIER = null;

    /**
     * The application instance.
     * 
     * @var Application
     */
    protected Application $application;

    /**
     * The dependecy intefection container.
     * 
     * @var FactoryDefault
     */
    protected FactoryDefault $container;

    /**
     * Construct the service provider class instance.
     *
     * @param FactoryDefault $container
     * @return void
     */
    public function __construct(FactoryDefault $container)
    {
        if (static::IDENTIFIER === null) {
            throw new Exception("The " . get_class($this) . " class must have an IDENTIFIER constant defined.");
        }

        $this->container = $container;
        $this->application = $this->container->getShared(Application::IDENTIFIER);
    }
}
