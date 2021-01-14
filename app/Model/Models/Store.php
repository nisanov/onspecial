<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use App\Service\Services\StoreScrapperInterface;

/**
 * @method static \App\Model\Models\Store[] find(array $parameters = null)
 * @method static \App\Model\Models\Store findFirst(array $parameters = null)
 * @method static \App\Model\Models\Store findFirstByName(string $name)
 * @method \Phalcon\Mvc\Model\Resultset\Simple|\App\Model\Models\Product[] getProducts()
 * @method \Phalcon\Mvc\Model\Resultset\Simple|\App\Model\Models\Special[] getSpecials()
 * @method \Phalcon\Mvc\Model\Resultset\Simple|\App\Model\Models\Scrape[] getScrapes()
 */
class Store extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The store name.
     * 
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The active state.
     * 
     * @var string
     */
    public int $active = 1;

    /**
     * The scrapper class.
     * 
     * @var string|null
     */
    public ?string $scrapper = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->hasMany('id', Product::class, 'store_id', [
            'reusable' => true,
            'alias' => 'products',
        ]);

        $this->hasMany('id', Special::class, 'store_id', [
            'reusable' => true,
            'alias' => 'specials',
        ]);

        $this->hasMany('id', Scrape::class, 'store_id', [
            'reusable' => true,
            'alias' => 'scrapes',
        ]);
    }

    /**
     * Get the scrapper class instance.
     *
     * @return StoreScrapperInterface
     */
    public function getScrapperInstance(): StoreScrapperInterface
    {
        return new $this->scrapper;
    }

    /**
     * Get the first matching or created store by name.
     *
     * @param string $name
     * @param array $assignment
     * @return self
     */
    public static function getFirstOrCreatedByName(string $name, array $assignment = []): self
    {
        $store = self::findFirstByName($name);
        if ($store === null) {
            $store = new self;
            $store->name = $name;
            $store->assign($assignment);
            $store->save();
        }

        return $store;
    }
}
