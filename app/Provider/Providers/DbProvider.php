<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Profiler\Item;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;

class DbProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'db';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $application = $this->application;

        $this->container->setShared(static::IDENTIFIER, function () use ($application) {

            $config = $application->getConfig()->path('database')->toArray();
            $params = [
                'host' => $config['host'],
                'username' => $config['username'],
                'password' => $config['password'],
                'dbname' => $config['dbname'],
                'charset' => $config['charset'],
                'collation' => $config['collation'],
            ];

            if (!$application->isDebug()) {
                return new Mysql($params);
            }

            $profiler = $application->getProfiler();
            $logger = $application->getLogger();

            $manager = new Manager;
            $manager->attach('db:beforeQuery', function (Event $event, Mysql $connection) use ($profiler) {
                $profiler->startProfile($connection->getSQLStatement(), $connection->getSqlVariables(), $connection->getSQLBindTypes());
            });
            $manager->attach('db:afterQuery', function () use ($profiler, $logger) {
                $profile = $profiler->stopProfile()->getLastProfile();
                $logger->info("", [
                    'statement' => self::getRawSqlStatement($profile),
                    'seconds' => $profile->getTotalElapsedSeconds(),
                    'profiler' => [
                        'statements' => $profiler->getNumberTotalStatements(),
                        'seconds' => $profiler->getTotalElapsedSeconds(),
                    ],
                ]);
            });

            $connection = new Mysql($params);
            $connection->setEventsManager($manager);

            return $connection;
        });
    }

    /**
     * Get the combined raw statement generated from the profile item.
     *
     * @return string
     */
    public static function getRawSqlStatement(Item $profile): string
    {
        $statement = $profile->getSqlStatement();
        $variables = $profile->getSqlVariables();

        if (empty($variables)) {
            return $statement;
        } elseif (key($variables) === 0) {
            return vsprintf(str_replace('?', "'%s'", $statement), $variables);
        }

        foreach ($variables as $variable => $value) {
            if (strpos($variable, ':') !== 0) {
                $variable = ":{$variable}";
            }
            if (!is_numeric($value)) {
                $value = "'{$value}'";
            }
            $statement = str_replace($variable, $value, $statement);
        }

        return $statement;
    }
}
