<?php

declare(strict_types=1);

namespace App\Task\Tasks;

use Phalcon\Cli\Task;
use Psy\Configuration;
use Psy\Shell;
use Psy\VersionUpdater\Checker;
use Throwable;
use Whoops\Exception\Formatter;
use Whoops\Exception\Inspector;

class PsyshTask extends Task
{
    /**
     * The class alias mapping.
     * 
     * @var array
     */
    protected array $classes = [
        'Brand' => 'App\Model\Models\Brand',
        'Product' => 'App\Model\Models\Product',
        'Scrape' => 'App\Model\Models\Scrape',
        'Special' => 'App\Model\Models\Special',
        'Store' => 'App\Model\Models\Store',
        'User' => 'App\Model\Models\User',
        'Woolworths' => 'App\Service\Services\StoreScrapper\Woolworths',
        'Authentication' => 'App\Service\Services\Authentication',
        'Mailer' => 'App\Service\Services\Mailer',
        'Redirector' => 'App\Service\Services\Redirector',
        'Url' => 'App\Service\Services\Url',
    ];

    /**
     * The psy shell instance.
     * 
     * @var Shell
     */
    protected Shell $shell;

    /**
     * The main task entry point.
     *
     * @return int
     */
    public function mainAction(): int
    {
        $config = new Configuration();
        $config->setUpdateCheck(Checker::NEVER);

        $this->shell = new Shell($config);
        $this->shell->setScopeVariables([
            'application' => $this->application,
        ]);

        spl_autoload_register([$this, 'alias']);

        try {
            return $this->shell->run();
        } catch (Throwable $exception) {
            fwrite(STDERR, Formatter::formatExceptionPlain(new Inspector($exception)));
        } finally {
            spl_autoload_unregister([$this, 'alias']);
        }
    }

    /**
     * Create a class alias, as applicable.
     *
     * @param string $class
     * @return void
     */
    public function alias(string $class): void
    {
        if (!isset($this->classes[$class])) {
            return;
        }

        $this->shell->writeStdout("[!] Aliasing '{$class}' to '{$this->classes[$class]}' for this psy shell session.\n");

        class_alias($this->classes[$class], $class);
    }
}
