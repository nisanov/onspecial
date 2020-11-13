<?php

$basePath = dirname(__DIR__);

return [
    'application' => [
        'baseUri' => $_ENV['APP_BASE_URI'],
        'crypticKey' => $_ENV['APP_CRYPTIC_KEY'],
    ],
    'mailer' => [
        'host' => $_ENV['MAIL_HOST'],
        'port' => $_ENV['MAIL_PORT'],
        'username' => $_ENV['MAIL_USERNAME'],
        'password' => $_ENV['MAIL_PASSWORD'],
    ],
    'database' => [
        'adapter' => $_ENV['DB_ADAPTER'],
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'redis' => [
        'host' => $_ENV['REDIS_HOST'],
        'port' => $_ENV['REDIS_PORT'],
        'password' => $_ENV['REDIS_PASSWORD'],
    ],
];
