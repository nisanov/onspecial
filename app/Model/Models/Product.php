<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @method static \App\Model\Models\Product[] find(array $parameters = null)
 * @method static \App\Model\Models\Product findFirst(array $parameters = null)
 * @method \App\Model\Models\Brand getBrand()
 * @method \App\Model\Models\Store getStore()
 * @method \App\Model\Models\Special getSpecial()
 * @method \Phalcon\Mvc\Model\Resultset\Simple|\App\Model\Models\Special[] getSpecials()
 */
class Product extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The brand record identifier.
     * 
     * @var int|null
     */
    private ?int $brand_id = null;

    /**
     * The store record identifier.
     * 
     * @var int|null
     */
    private ?int $store_id = null;

    /**
     * The store record reference identifier.
     * 
     * @var string|null
     */
    private ?string $store_reference = null;

    /**
     * The product name.
     * 
     * @var string|null
     */
    private ?string $name = null;

    /**
     * The product description.
     * 
     * @var string|null
     */
    private ?string $description = null;

    /**
     * The ingredients.
     * 
     * @var string|null
     */
    private ?string $ingredients = null;

    /**
     * The link to the store product.
     * 
     * @var string|null
     */
    private ?string $uri = null;

    /**
     * The link to the store product thumbnail image.
     * 
     * @var string|null
     */
    private ?string $thumb = null;

    /**
     * The link to the store product image.
     * 
     * @var string|null
     */
    private ?string $image = null;

    /**
     * The size information.
     * 
     * @var string|null
     * 
     */
    private ?string $size = null;

    /**
     * Whether the product is currently on special.
     * 
     * @var int
     */
    private int $on_special = 1;

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

    /**
     * Get the unique record identifier.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the brand record identifier.
     *
     * @return int|null
     */
    public function getBrandId(): ?int
    {
        return $this->brand_id;
    }

    /**
     * Get the store record identifier.
     *
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    /**
     * Get the store record reference identifier.
     *
     * @return string|null
     */
    public function getStoreReference(): ?string
    {
        return $this->store_reference;
    }

    /**
     * Get the product name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the product description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the link to the store product.
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Get the link to the store product thumbnail image.
     *
     * @return string|null
     */
    public function getThumb(): ?string
    {
        return $this->thumb;
    }

    /**
     * Get the link to the store product image.
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Get whether this product is currently on special.
     *
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * Get or set whether this product is currently on special.
     *
     * @param bool|null $state
     * @return bool
     */
    public function isOnSpecial(?bool $state = null): bool
    {
        if ($state !== null) {
            $this->on_special = (int)$state;
        }

        return (bool)$this->on_special;
    }

    /**
     * Set the brand record identifier.
     *
     * @param int|null $value
     * @return void
     */
    public function setBrandId(?int $value = null): void
    {
        $this->brand_id = $value;
    }

    /**
     * Set the store record identifier.
     *
     * @param int $value
     * @return void
     */
    public function setStoreId(int $value): void
    {
        $this->store_id = $value;
    }

    /**
     * Set the store record reference identifier.
     *
     * @param string $value
     * @return void
     */
    public function setStoreReference(string $value): void
    {
        $this->store_reference = $value;
    }

    /**
     * Set the product name.
     *
     * @param string $value
     * @return void
     */
    public function setName(string $value): void
    {
        $this->name = $value;
    }

    /**
     * Set the product description.
     *
     * @param string $value
     * @return void
     */
    public function setDescription(string $value): void
    {
        $this->description = $value;
    }

    /**
     * Set the link to the store product.
     *
     * @param string $value
     * @return void
     */
    public function setUri(string $value): void
    {
        $this->uri = $value;
    }

    /**
     * Set the link to the store product thumbnail image.
     *
     * @param string $value
     * @return void
     */
    public function setThumb(string $value): void
    {
        $this->thumb = $value;
    }

    /**
     * Set the link to the store product image.
     *
     * @param string $value
     * @return void
     */
    public function setImage(string $value): void
    {
        $this->image = $value;
    }

    /**
     * Set whether this product is currently on special.
     *
     * @param string $value
     * @return void
     */
    public function setSize(string $value): void
    {
        $this->size = $value;
    }

    /**
     * Get the first updated or created product matching store.
     *
     * @param int $storeId
     * @param string $storeReference
     * @param array $assignment
     * @return self
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
            $product->setStoreId($storeId);
            $product->setStoreReference($storeReference);
        }

        $product->assign($assignment);
        $product->save();

        return $product;
    }
}
