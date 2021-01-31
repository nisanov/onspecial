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

            fwrite(STDOUT, "Performing scrape for store: $store->name" . PHP_EOL);

            $store->getScrapperInstance()->performScrape();

            fwrite(STDOUT, "Completed scrape for store: $store->name" . PHP_EOL);
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
            fwrite(STDOUT, "Unknown store specified: $storeName" . PHP_EOL);
            return -1;
        }

        fwrite(STDOUT, "Performing scrape for store: $storeName" . PHP_EOL);

        $store->getScrapperInstance()->performScrape();

        fwrite(STDOUT, "Completed scrape for store: $storeName" . PHP_EOL);

        return 0;
    }
}
