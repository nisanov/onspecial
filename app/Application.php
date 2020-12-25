<?php

declare(strict_types=1);

namespace App;

use App\Provider\GenericInterface;
use App\Service\Services\Toggle;
use Dotenv\Dotenv;
use Exception;
use Phalcon\Cli\Console;
use Phalcon\Config;
use Phalcon\Db\Profiler;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Http\ResponseInterface;
use Phalcon\Loader;
use Phalcon\Logger;
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
     * The MVC application request.
     * 
     * @var string|null
     */
    protected string $request;

    /**
     * The MVC application response.
     * 
     * @var ResponseInterface|null
     */
    protected ResponseInterface $response;

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
        $this->container->setShared('toggle', Toggle::class);

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
        $this->request = $request;
        $this->response = $this->application->handle($this->request);
        if ($this->response instanceof ResponseInterface) {
            echo $this->response->getContent();
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
     * Determine whether the current application environment is production.
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->getConfig()->path('application.environment') === 'production';
    }

    /**
     * Determine whether the current application is in debug mode.
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->getConfig()->path('application.debug') === true;
    }

    /**
     * Get the MVC application request.
     *
     * @return string|null
     */
    public function getRequest(): ?string
    {
        return $this->request;
    }

    /**
     * Get the MVC application response.
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
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
     * Get the application logger service provider.
     * 
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->container->getLogger();
    }

    /**
     * Get the application database profiler service provider.
     * 
     * @return Profiler
     */
    public function getProfiler(): Profiler
    {
        return $this->container->getProfiler();
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

        $providers = require $path;
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
