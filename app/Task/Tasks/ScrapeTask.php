<?php

declare(strict_types=1);

namespace App\Task\Tasks;

use App\Model\Models\Store;
use Phalcon\Cli\Task;

class ScrapeTask extends Task
{
    /**
     * The main task entry point.
     *
     * @return int
     */
    public function mainAction(): int
    {
        foreach (Store::find('active') as $store) {

            fwrite(STDOUT, "Performing scrape for store: " . $store->getName() . PHP_EOL);

            $store->getScrapperInstance()->performScrape();

            fwrite(STDOUT, "Completed scrape for store: " . $store->getName() . PHP_EOL);
        }

        return 0;
    }
}
