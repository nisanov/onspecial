<?php

declare(strict_types=1);

namespace App\Task\Tasks;

use App\Model\Models\Store;
use App\Task\Generic;

class ScrapperTask extends Generic
{
    /**
     * The main task entry point.
     *
     * @return int
     */
    public function mainAction(): int
    {
        foreach (Store::find('active') as $store) {

            $this->logger->info("Performing scrape for store: {$store->name}.");

            $store->getScrapperInstance()->performScrape();

            $this->logger->info("Completed scrape for store: {$store->name}.");
        }

        return 0;
    }

    /**
     * The main task entry point.
     *
     * @param string $storeName
     * @return int
     */
    public function scrapeAction(string $storeName): int
    {
        $store = Store::findFirstByName($storeName);
        if (!$store instanceof Store) {
            $this->logger->info("Unknown store specified: {$storeName}.");
            return -1;
        }

        $this->logger->info("Performing scrape for store: {$store->name}.");

        $store->getScrapperInstance()->performScrape();

        $this->logger->info("Completed scrape for store: {$store->name}.");

        return 0;
    }
}
