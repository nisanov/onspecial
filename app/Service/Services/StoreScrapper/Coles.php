<?php

declare(strict_types=1);

namespace App\Service\Services\StoreScrapper;

use App\Repository\Repositories\BrandRepository;
use App\Service\Services\StoreScrapper;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Generator;
use Symfony\Component\DomCrawler\Crawler;

class Coles extends StoreScrapper
{
    /**
     * The catalogue URL.
     *
     * @var string
     */
    protected string $catalogueURL;

    /**
     * Get the products on special generator.
     *
     * @return Generator
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getProducts(): Generator
    {
        $page = 1;
        $records = 0;

        $url = "https://salefinder.com.au";

        $this->client->request('GET', $url);
        $crawler = $this->client->waitForVisibility('#frmRegion');

        $form = $crawler->filter('#frmRegion')->form();
        $form['locationSearch']->setValue('3000');

        $crawler = $this->client->waitFor('.autocomplete-suggestion');
        $crawler->filter('.autocomplete-suggestion')->click();

        $this->client->waitFor('#shop-by-retailer-tab');
        $this->client->request('GET', "$url/Coles-catalogue");
        $crawler = $this->client->waitFor('#retailer-catalogues');
        $crawler->filter('#retailer-catalogues')->filterXPath('//div[text()="Coles Catalogue VIC METRO"]')->parents()->filter('.catalogue-image')->click();
        $crawler = $this->client->waitFor('#sf-product-list');
        $crawler->filter('#sf-product-list')->click();
        $crawler = $this->client->waitFor('#catalogues-view');

        $this->catalogueURL = $this->client->getCurrentURL();

        while (true) {
            try {
                $this->logger->info("Scrapping {$this->store->name} on page {$page}.");
                $generator = $this->getSpecialsOnPage($page++, $crawler);
                foreach ($generator as $special) {
                    yield $special;
                }

                $records += $generator->getReturn();

                sleep(rand(2, 3));
            } catch (\OutOfRangeException $exception) {
                break;
            }
        }

        return $records;
    }

    /**
     * Get the scrapper's store name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Coles';
    }

    /**
     * Get the specials generator for the specified page number.
     *
     * @param int $page
     * @param Crawler $crawler
     * @return Generator
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function getSpecialsOnPage(int $page, Crawler  $crawler): Generator
    {
        $records = 0;

        if ($page !== 1) {
            $this->client->request('GET', "{$this->catalogueURL}?qs=$page");
            $crawler = $this->client->waitFor('#catalogues-view');
        }

        /** @var WebDriverElement $product */
        $products = $crawler->filter('#catalogues-view .item-landscape');
        if ($products->count() <= 0) {
            throw new \OutOfRangeException("Not more products remaining.");
        }

        foreach ($products as $product) {

            $description = $product->findElement(WebDriverBy::cssSelector('.item-description'))->getAttribute('innerHTML');
            if ($description != "1/2 PRICE") {
                continue;
            }

            $thumbSrc = $product->findElement(WebDriverBy::cssSelector('.item-image img'))->getAttribute('src');
            $imageSrc = str_replace('/thumbs/ipad/', '/products/350x/', $thumbSrc);

            $itemAnchor = $product->findElement(WebDriverBy::cssSelector('.item-actions a'));

            $name = $itemAnchor->getAttribute('data-itemname');

            $priceOptions = $product->findElement(WebDriverBy::cssSelector('.price-options'));
            preg_match('/Was \$([0-9\.]+), save \$([0-9\.]+)/i', $priceOptions->getAttribute('innerText'), $match);

            $ratio = "";
            if (strpos($priceOptions->getAttribute('innerHTML'), 'comparative-text') !== false) {
                $ratio = str_replace('per', '/', trim($priceOptions->findElement(WebDriverBy::cssSelector('.comparative-text'))->getAttribute('innerText')));
            }

            $records++;

            yield [
                'record' => $records,
                'store_id' => $this->store->id,
                'store_reference' => trim($itemAnchor->getAttribute('data-itemid')),
                'brand' => (new BrandRepository)->getBrandName($name),
                'name' => html_entity_decode(trim(preg_replace('!\s+!', ' ', $name))),
                'description' => '',
                'ingredients' => '',
                'uri' => $itemAnchor->getAttribute('href'),
                'thumb' => $thumbSrc,
                'image' => $imageSrc,
                'size' => '',
                'price' => [
                    'current' => ($match[1] ?? 0.00) - ($match[2] ?? 0.00),
                    'previous' => $match[1] ?? 0.00,
                    'ratio' => $ratio,
                ],
            ];
        }

        return $records;
    }
}
