<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @method static Special[] find(array $parameters = null)
 * @method static Special findFirst(array $parameters = null)
 * @method Store getStore()
 * @method Store getProduct()
 */
class Special extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The store record identifier.
     * 
     * @var int|null
     */
    private ?int $store_id = null;

    /**
     * The product record identifier.
     * 
     * @var int|null
     */
    private ?int $product_id = null;

    /**
     * The current price.
     * 
     * @var float
     */
    private float $current_price = 0.00;

    /**
     * The previous price.
     * 
     * @var float
     */
    private float $previous_price = 0.00;

    /**
     * The ratio price.
     * 
     * @var string|null
     */
    private ?string $ratio_price = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->belongsTo('id', Store::class, 'store_id', [
            'reusable' => true,
            'alias' => 'store',
        ]);

        $this->belongsTo('id', Product::class, 'product_id', [
            'reusable' => true,
            'alias' => 'product',
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
     * Get the store record identifier.
     *
     * @return int|nullz
     */
    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    /**
     * Get the product record identifier.
     *
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    /**
     * Get the current price.
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->current_price;
    }

    /**
     * Get the previous price.
     *
     * @return float
     */
    public function getPreviousPrice(): float
    {
        return $this->previous_price;
    }

    /**
     * Get the ratio price.
     *
     * @return string|null
     */
    public function getRatioPrice(): ?string
    {
        return $this->ratio_price;
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
     * Set the product record identifier.
     *
     * @param int $value
     * @return void
     */
    public function setProductId(int $value): void
    {
        $this->product_id = $value;
    }

    /**
     * Set the current price.
     *
     * @param float $value
     * @return void
     */
    public function setCurrentPrice(float $value): void
    {
        $this->current_price = $value;
    }

    /**
     * Set the previous price.
     *
     * @param float $value
     * @return void
     */
    public function setPreviousPrice(float $value): void
    {
        $this->previous_price = $value;
    }

    /**
     * Set the ratio price.
     *
     * @param string $value
     * @return void
     */
    public function setRatioPrice(string $value): void
    {
        $this->ratio_price = $value;
    }
}
