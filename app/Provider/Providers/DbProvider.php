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
        $config = $this->application->getConfig()->path('database')->toArray();
        $profiler = $this->application->getProfiler();
        $logger = $this->application->getLogger();

        $params = [
            'host' => $config['host'],
            'username' => $config['username'],
            'password' => $config['password'],
            'dbname' => $config['dbname'],
            'charset' => $config['charset'],
            'collation' => $config['collation'],
        ];

        $this->container->setShared(static::IDENTIFIER, function () use ($profiler, $logger, $params) {

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
        $search = [];
        $replace = [];
        foreach ((array)$profile->getSqlVariables() as $key => $value) {
            if (is_numeric($key)) {
                $key = '?';
            } elseif (strpos($key, ':') !== 0) {
                $key = ":{$key}";
            }
            $search[] = $key;
            if (!is_numeric($value)) {
                $value = "'{$value}'";
            }
            $replace[] = $value;
        }

        $statement = str_replace('%s', '%%s', $profile->getSqlStatement());
        $statement = str_replace(array_unique($search), '%s', $statement);

        return sprintf($statement, ...$replace);
    }
}
