<?php

declare(strict_types=1);

namespace App;

use App\Provider\GenericInterface;
use Dotenv\Dotenv;
use Exception;
use Phalcon\Cli\Console;
use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Http\ResponseInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Application as MvcApplication;
use Phalcon\Security;
use Phalcon\Session\Manager as SessionManager;
use Throwable;
use Whoops\Exception\Formatter;
use Whoops\Exception\Inspector;

class Application
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'application';

    /**
     * The application configuration
     * 
     * @var Config|null
     */
    protected ?Config $configuration = null;

    /**
     * The application instance.
     * 
     * @var MvcApplication
     */
    protected MvcApplication $application;

    /**
     * The console instance.
     */
    protected Console $console;

    /**
     * The application container.
     * 
     * @var FactoryDefault
     */
    protected FactoryDefault $container;

    /**
     * The application base path.
     * 
     * @var string
     */
    protected string $basePath;

    /**
     * Whether the application is being run at the command line.
     */
    protected bool $cli = false;

    /**
     * Construct the application class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->cli = php_sapi_name() === 'cli';
        $this->basePath = dirname(__DIR__);

        date_default_timezone_set('Australia/Melbourne');

        Dotenv::createImmutable($this->basePath)->load();

        $this->container = $this->cli ? new Cli : new FactoryDefault();
        $this->container->setShared(self::IDENTIFIER, $this);

        if ($this->cli) {
            $this->console = new Console($this->container);
            $this->container->setShared('console', $this->console);
        } else {
            $this->application = new MvcApplication($this->container);
        }

        $this->registerProviders();
        $this->registerNamespaces();
    }

    /**
     * Run the application.
     * 
     * @param string $request
     * @return void
     */
    public function run(string $request): void
    {
        $response = $this->application->handle($request);
        if ($response instanceof ResponseInterface) {
            echo $response->getContent();
        }
    }

    /**
     * Run the application at the command line.
     *
     * @param array|null $arguments
     * @return void
     */
    public function runCommandLine(?array $arguments = []): void
    {
        try {
            $this->console->handle($arguments);
        } catch (Throwable $exception) {
            fwrite(STDERR, Formatter::formatExceptionPlain(new Inspector($exception)));
        }
    }

    /**
     * Get whether the application is being run at the command line.
     * 
     * @return bool
     */
    public function isCommandLine(): bool
    {
        return $this->cli;
    }

    /**
     * Get the application base path.
     * 
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get the application container.
     * 
     * @return FactoryDefault
     */
    public function getContainer(): FactoryDefault
    {
        return $this->container;
    }

    /**
     * Get the application configuration service provider.
     * 
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->container->getConfig();
    }

    /**
     * Get the application session service provider.
     * 
     * @return SessionManager
     */
    public function getSession(): SessionManager
    {
        return $this->container->getSession();
    }

    /**
     * Get the application security service provider.
     * 
     * @return Security
     */
    public function getSecurity(): Security
    {
        return $this->container->getSecurity();
    }

    /**
     * Register application providers.
     * 
     * @return void
     */
    private function registerProviders(): void
    {
        $path = $this->basePath . '/app_config/providers.php';
        if (!file_exists($path) || !is_readable($path)) {
            throw new Exception('Missing or unreadable providers file at path: ' . $path);
        }

        $providers = require_once $path;
        foreach ($providers as $className) {
            /** @var GenericInterface $provider */
            $provider = new $className($this->container);
            $provider->register();
        }
    }

    /**
     * Register the application namespaces.
     *
     * @return void
     */
    private function registerNamespaces(): void
    {
        $loader = new Loader();
        $loader->registerNamespaces(['App' => $this->basePath . '/app/']);
        $loader->register();
    }
}
