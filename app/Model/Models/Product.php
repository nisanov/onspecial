<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @method static Product findFirst(array $parameters = null)
 * @method Brand getBrand()
 * @method Store getStore()
 */
class Product extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var string|null
     */
    private ?string $id = null;

    /**
     * The brand record identifier.
     * 
     * @var string
     */
    private string $brand_id;

    /**
     * The store record identifier.
     * 
     * @var string
     */
    private string $store_id;

    /**
     * The store record reference identifier.
     * 
     * @var string
     */
    private string $store_reference;

    /**
     * The product name.
     * 
     * @var string
     */
    private string $name;

    /**
     * The product description.
     * 
     * @var string
     */
    private string $description;

    /**
     * The ingredients.
     * 
     * @var string
     */
    private string $ingredients;

    /**
     * The link to the store product.
     * 
     * @var string
     */
    private string $uri;

    /**
     * The link to the store product thumbnail image.
     * 
     * @var string
     */
    private string $thumb;

    /**
     * The link to the store product image.
     * 
     * @var string
     */
    private string $image;

    /**
     * The size information.
     * 
     * @var string
     */
    private string $size;

    /**
     * Whether the product is currently on special.
     * 
     * @var bool
     */
    private bool $on_special = true;

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
    }

    /**
     * Get the unique record identifier.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the brand record identifier.
     *
     * @param string $value
     * @return void
     */
    public function setBrandId(string $value): void
    {
        $this->brand_id = $value;
    }

    /**
     * Get the brand record identifier.
     *
     * @return string
     */
    public function getBrandId(): string
    {
        return $this->brand_id;
    }

    /**
     * Set the store record identifier.
     *
     * @param string $value
     * @return void
     */
    public function setStoreId(string $value): void
    {
        $this->store_id = $value;
    }

    /**
     * Get the store record identifier.
     *
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->store_id;
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
     * Get the store record reference identifier.
     *
     * @return string
     */
    public function getStoreReference(): string
    {
        return $this->store_reference;
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
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * Get the product description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
     * Get the link to the store product.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
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
     * Get the link to the store product thumbnail image.
     *
     * @return string
     */
    public function getThumb(): string
    {
        return $this->thumb;
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
     * Get the link to the store product image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the size information.
     *
     * @param string $value
     * @return void
     */
    public function setSize(string $value): void
    {
        $this->size = $value;
    }

    /**
     * Get the size information.
     *
     * @return string
     */
    public function getSize(): string
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
            $this->on_special = $state;
        }

        return $this->on_special;
    }

    /**
     * Determines whether this product should create an active special.
     *
     * @return bool
     */
    public function shouldCreateSpecial(): bool
    {
        if ($this->on_special && $this->hasChanged('on_special')) {
            return true;
        }

        return $this->isNewlyCreated();
    }

    /**
     * Get the first updated or created product matching store.
     *
     * @param string $storeId
     * @param string $storeReference
     * @param array $assignment
     * @return self
     */
    public static function getUpdatedOrCreatedByStore(string $storeId, string $storeReference, array $assignment = []): self
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
