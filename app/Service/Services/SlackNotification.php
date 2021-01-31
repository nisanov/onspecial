<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Application;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class SlackNotification
{
    /**
     * The guzzle client.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * The application environment.
     *
     * @var string
     */
    protected string $environment = "";

    /**
     * Construct the class instance and guzzle client.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => "https://hooks.slack.com"]);
        $this->environment = Application::getDefault()->getEnvironment();
    }

    /**
     * Send the slack notification.
     *
     * @return void
     * @throws GuzzleException
     */
    final public function send(): void
    {
        $this->client->request('POST', $this->getWebHookUrl(), [
            'body' => json_encode($this->getWebHookData()),
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Get the webhook url.
     *
     * @return string
     */
    abstract public function getWebHookUrl(): string;

    /**
     * Get the webhook data.
     *
     * @return array
     */
    abstract public function getWebHookData(): array;
}
