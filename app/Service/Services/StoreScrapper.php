<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Model\Models\Brand;
use App\Model\Models\Product;
use App\Model\Models\Scrape;
use App\Model\Models\Special;
use App\Model\Models\Store;
use App\Service\Generic;
use Carbon\Carbon;
use Phalcon\Db\Exception as DbException;
use Symfony\Component\Panther\Client;
use Throwable;
use Whoops\Exception\Formatter;
use Whoops\Exception\Inspector;

abstract class StoreScrapper extends Generic implements StoreScrapperInterface
{
    /**
     * The scrape information.
     *
     * @var Scrape
     */
    protected Scrape $scrape;

    /**
     * The store scrapping.
     *
     * @var Store
     */
    protected Store $store;

    /**
     * The panther browser client.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Chrome client arguments.
     *
     * @var array
     */
    protected array $arguments = [
        '--headless',
        '--incognito',
        '--no-sandbox',
        '--disable-gpu',
        '--disable-dev-shm-usage',
        '--window-size=1920,1080'
    ];

    /**
     * The number of records last scrapped.
     *
     * @var int
     */
    protected int $recordsScrapped = 0;

    /**
     * The number of records last expired.
     *
     * @var int
     */
    protected int $recordsExpired = 0;

    /**
     * Construct an instance of the store scrapper.
     *
     * @return void
     * @throws DbException
     */
    public function __construct()
    {
        $this->store = Store::getFirstOrCreatedByName($this->getName(), ['scrapper' => get_class($this), 'created_at' => Carbon::now()->toDateTimeString('microseconds')]);
        $this->client = Client::createChromeClient('/code/bin/chromedriver', $this->arguments);

        $this->scrape = new Scrape;
        $this->scrape->store_id = $this->store->id;
    }

    /**
     * Perform the store scrapper.
     *
     * @return void
     * @throws DbException
     * @throws Throwable
     */
    final public function performScrape(): void
    {
        try {

            $this->scrape->save();

            $generator = $this->getProducts();

            foreach ($generator as $data) {

                $product = Product::getUpdatedOrCreatedByStore($data['store_id'], $data['store_reference'], [
                    'on_special' => '1',
                    'brand_id' => $data['brand'] ? Brand::getFirstOrCreatedByName($data['brand'])->id : null,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'ingredients' => $data['ingredients'],
                    'uri' => $data['uri'],
                    'thumb' => $data['thumb'],
                    'image' => $data['image'],
                    'size' => $data['size'],
                ]);

                if ($product->hasUpdated('id') || $product->hasUpdated('on_special')) {

                    $assignment = [
                        'store_id' => $data['store_id'],
                        'product_id' => $product->id,
                        'current_price' => $data['price']['current'],
                        'previous_price' => $data['price']['previous'],
                        'ratio_price' => $data['price']['ratio']
                    ];

                    $special = new Special;
                    $special->assign($assignment);
                    $special->save();
                }

                $this->scrape->records++;
            }

            $this->recordsScrapped = (int)$generator->getReturn();
            $this->archiveExpiredSpecials(); // archive must happen before the store is saved and the updated at is
            $this->store->save();
        } catch (Throwable $exception) {
            $this->scrape->error = Formatter::formatExceptionPlain(new Inspector($exception));
            throw $exception;
        } finally {
            $this->scrape->save();
        }
    }

    /**
     * Archive expired specials for this store instance.
     *
     * @return void
     * @throws DbException
     */
    public function archiveExpiredSpecials(): void
    {
        $products = Product::find([
            "on_special AND store_id = :storeId: AND ( updated_at < :updatedAt: OR ( updated_at IS NULL AND created_at < :updatedAt: ))",
            'bind' => [
                'storeId' => $this->store->id,
                'updatedAt' => $this->store->updated_at,
            ]
        ]);

        foreach ($products as $product) {
            $product->on_special = 0;
            $product->save();
            if ($product->special !== null) {
                $product->special->save();
            }
        }

        $this->recordsExpired = count($products);
        $this->scrape->expired = $this->recordsExpired;
    }

    /**
     * Get the number of records last scrapped.
     *
     * @return int
     */
    public function getRecordsScrapped(): int
    {
        return $this->recordsScrapped;
    }

    /**
     * Get the number of records last expired.
     *
     * @return int
     */
    public function getRecordsExpired(): int
    {
        return $this->recordsExpired;
    }
}
