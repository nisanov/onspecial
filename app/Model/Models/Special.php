<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @method Store getStore()
 * @method Store getProduct()
 */
class Special extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var string|null
     */
    private ?string $id = null;

    /**
     * The store record identifier.
     * 
     * @var string
     */
    private string $store_id;

    /**
     * The product record identifier.
     * 
     * @var string
     */
    private string $product_id;

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
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * Set the product record identifier.
     *
     * @param string $value
     * @return void
     */
    public function setProductId(string $value): void
    {
        $this->product_id = $value;
    }

    /**
     * Get the product record identifier.
     *
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product_id;
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
     * Get the current price.
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->current_price;
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
     * Get the previous price.
     *
     * @return float
     */
    public function getPreviousPrice(): float
    {
        return $this->previous_price;
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

    /**
     * Get the ratio price.
     *
     * @return string|null
     */
    public function getRatioPrice(): ?string
    {
        return $this->ratio_price;
    }
}
