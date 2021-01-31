<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use App\Service\Services\StoreScrapperInterface;
use Phalcon\Db\Exception as DbException;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property-read Simple|Product[] $products
 * @property-read Simple|Special[] $specials
 * @property-read Simple|Scrape[] $scrapes
 *
 * @method static Store[] find(array|string $parameters = null)
 * @method static Store findFirst(array|string|int|null $parameters = null)
 * @method static Store findFirstByName(string $name)
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
     * @var int
     */
    public int $active = 1;

    /**
     * The scrapper class.
     *
     * @var string|null
     */
    public ?string $scrapper = null;

    /**
     * Get the first matching or created store by name.
     *
     * @param string $name
     * @param array $assignment
     * @return self
     * @throws DbException
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
}
