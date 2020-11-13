<?php

declare(strict_types=1);

namespace App\Service\Services;

use Generator;

interface StoreScrapperInterface
{
    /**
     * Get the scrapper's store name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the products on special generator.
     *
     * @return Generator
     */
    public function getProducts(): Generator;
}
