<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Phalcon\Db\Exception as DbException;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property-read Brand $brand
 * @property-read Store $store
 * @property-read Special $special
 * @property-read Simple|Special[] $specials
 *
 * @method static Product[] find(array|string $parameters = null)
 * @method static Product findFirst(array|string|int|null $parameters = null)
 */
class Product extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The brand record identifier.
     *
     * @var int|null
     */
    public ?int $brand_id = null;

    /**
     * The store record identifier.
     *
     * @var int|null
     */
    public ?int $store_id = null;

    /**
     * The store record reference identifier.
     *
     * @var string|null
     */
    public ?string $store_reference = null;

    /**
     * The product name.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * The product description.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * The ingredients.
     *
     * @var string|null
     */
    public ?string $ingredients = null;

    /**
     * The link to the store product.
     *
     * @var string|null
     */
    public ?string $uri = null;

    /**
     * The link to the store product thumbnail image.
     *
     * @var string|null
     */
    public ?string $thumb = null;

    /**
     * The link to the store product image.
     *
     * @var string|null
     */
    public ?string $image = null;

    /**
     * The size information.
     *
     * @var string|null
     *
     */
    public ?string $size = null;

    /**
     * Whether the product is currently on special.
     *
     * @var int
     */
    public int $on_special = 1;

    /**
     * Get the first updated or created product matching store.
     *
     * @param int $storeId
     * @param string $storeReference
     * @param array $assignment
     * @return self
     * @throws DbException
     */
    public static function getUpdatedOrCreatedByStore(int $storeId, string $storeReference, array $assignment = []): self
    {
        $condition = [
            'store_id = :storeId: AND store_reference = :storeReference:',
            'bind' => [
                'storeId' => $storeId,
                'storeReference' => $storeReference,
            ],
        ];

        $product = self::findFirst($condition);
        if ($product === null) {
            $product = new self;
            $product->store_id = $storeId;
            $product->store_reference = $storeReference;
        }

        $product->assign($assignment);
        $product->save();

        return $product;
    }

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->belongsTo('brand_id', Brand::class, 'id', [
            'reusable' => true,
            'alias' => 'brand',
        ]);

        $this->belongsTo('store_id', Store::class, 'id', [
            'reusable' => true,
            'alias' => 'store',
        ]);

        $this->hasMany('id', Special::class, 'product_id', [
            'reusable' => true,
            'alias' => 'specials',
        ]);

        $this->hasOne('id', Special::class, 'product_id', [
            'reusable' => true,
            'alias' => 'special',
            'params' => [
                'conditions' => "updated_at IS NULL",
            ]
        ]);

        $this->hasMany('id', UserProduct::class, 'product_id', [
            'reusable' => true,
            'alias' => 'productUsers',
        ]);

        $this->hasManyToMany('id', UserProduct::class, 'product_id', 'user_id', User::class, 'id', [
            'reusable' => true,
            'alias' => 'users',
        ]);
    }
}
