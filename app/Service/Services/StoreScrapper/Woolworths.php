<?php

declare(strict_types=1);

namespace App\Service\Services\StoreScrapper;

use App\Service\Services\StoreScrapper;
use Carbon\Carbon;
use Generator;

class Woolworths extends StoreScrapper
{
    /**
     * Get the scrapper's store name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Woolworths';
    }

    /**
     * Get the products on special generator.
     *
     * @return Generator
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getProducts(): Generator
    {
        $records = 0;

        $url = "https://www.woolworths.com.au/shop/browse/specials/half-price?pageNumber=1&sortBy=AvailableDate";

        $this->client->request('GET', $url);
        $crawler = $this->client->waitFor('.paging-pageNumber');

        $pages = $crawler->filter('.paging-pageNumber')->last()->text();
        if (empty($pages)) {
            $now = Carbon::now()->format('YmdHis');
            $this->client->takeScreenshot("/code/{$now}_{$this->getName()}_page_1_pages.png");
        }

        for ($page = 1; $page <= $pages; $page++) {

            $generator = $this->getSpecialsOnPage($page);
            foreach ($generator as $special) {
                yield $special;
            }

            $records += $generator->getReturn();
        }

        return $records;
    }

    /**
     * Get the specials generator for the specified page number.
     *
     * @param int $page
     * @return Generator
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getSpecialsOnPage(int $page): Generator
    {
        $records = 0;

        $url = "https://www.woolworths.com.au/shop/browse/specials/half-price?pageNumber={$page}&sortBy=AvailableDate";

        $this->client->request('GET', $url);
        $this->client->waitFor('.paging-pageNumber');

        sleep(rand(2, 4));
        $products = $this->client->executeScript("return wowDataLayer.browseResults.products") ?? [];
        if (empty($products)) {
            $now = Carbon::now()->format('YmdHis');
            $this->client->takeScreenshot("/code/{$now}_{$this->getName()}_page_{$page}_products.png");
        }

        foreach ($products as $product) {

            if (!isset($product['Stockcode'])) {
                continue;
            }

            if (!$product['IsOnSpecial']) {
                continue;
            }

            $records++;

            $data = [
                'record' => $records,
                'store_id' => $this->store->getId(),
                'store_reference' => trim((string)$product['Stockcode']),
                'brand' => ucwords(html_entity_decode(trim((string)$product['Brand']))),
                'name' => html_entity_decode(trim(preg_replace('!\s+!', ' ', (string)$product['Name']))),
                'description' => trim((string)($product['RichDescription'] ?? $product['FullDescription'])),
                'ingredients' => trim((string)$product['AdditionalAttributes']['ingredients']),
                'uri' => "https://www.woolworths.com.au/shop/productdetails/{$product['Stockcode']}/{$product['UrlFriendlyName']}",
                'thumb' => $product['SmallImageFile'],
                'image' => $product['LargeImageFile'],
                'size' => $product['PackageSize'],
                'price' => [
                    'current' => $product['Price'],
                    'previous' => $product['WasPrice'],
                    'ratio' => trim($product['HasCupPrice'] ? $product['CupString'] : ""),
                ],
            ];

            yield $data;
        }

        return $records;
    }
}
