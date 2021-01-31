<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use App\Service\Services\Mailer;
use Swift_SmtpTransport;

class MailerProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     *
     * @var string
     */
    const IDENTIFIER = 'mailer';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $view = $this->container->get('view');
        $config = $this->application->getConfig()->path('mailer')->toArray();
        $params = [
            'host' => $config['host'],
            'port' => $config['port'],
            'username' => $config['username'],
            'password' => $config['password'],
        ];

        $this->container->setShared(static::IDENTIFIER, function () use ($params, $view) {

            $transport = new Swift_SmtpTransport($params['host'], $params['port']);
            $transport->setUsername($params['username']);
            $transport->setPassword($params['password']);

            return new Mailer($view, $transport);
        });
    }
}
