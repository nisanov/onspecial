<?php

declare(strict_types=1);

namespace App\Service\Services\SlackNotification;

use App\Model\Models\Scrape;
use App\Service\Services\SlackNotification;

class StoreScrapper extends SlackNotification
{
    /**
     * The scrape performed.
     */
    protected Scrape $scrape;

    /**
     * Set the scrape performed.
     *
     * @param Scrape $scrape
     * @return self
     */
    public function setScrape(Scrape $scrape): self
    {
        $this->scrape = $scrape;

        return $this;
    }

    /**
     * Get the webhook url.
     *
     * @return string
     */
    public function getWebHookUrl(): string
    {
        /** @noinspection SpellCheckingInspection */
        return "/services/T017M9ZS9DW/B0199022DCG/J1ZrsWnvp9dvku3cml5E1mJE";
    }

    /**
     * Get the webhook data.
     *
     * @return array
     */
    public function getWebHookData(): array
    {
        $data = [
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "The following scrape has been performed:",
                    ],
                ],
                [
                    'type' => 'section',
                    'fields' => [
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Store:* {$this->scrape->store->name}",
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Environment:* {$this->environment}",
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Specials:* {$this->scrape->records}",
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Expired:* {$this->scrape->expired}",
                        ],
                        [
                            'type' => 'mrkdwn',
                            'text' => "*Seconds:* {$this->scrape->seconds}",
                        ],
                    ],
                ],
            ],
        ];

        if (!empty($this->scrape->error)) {
            $data['blocks'][] = [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => ":boom: The following error was encountered :boom:\n{$this->scrape->error}",
                ],
            ];
        }

        return $data;
    }
}
