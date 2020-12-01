<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;

/**
 * @method User getUser()
 * @method Product getProduct()
 */
class UserProduct extends Generic
{
    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The user record identifier.
     * 
     * @var int
     */
    private ?int $user_id = null;

    /**
     * The product record identifier.
     * 
     * @var int
     */
    private ?int $product_id = null;

    /**
     * Whether the product is a favorite.
     * 
     * @var int
     */
    private int $favorite = 0;

    /**
     * Whether the product is ignored.
     * 
     * @var int
     */
    private int $ignoring = 0;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('user_product');

        $this->belongsTo('user_id', User::class, 'id', [
            'reusable' => true,
            'alias'    => 'user'
        ]);

        $this->belongsTo('product_id', Product::class, 'id', [
            'reusable' => true,
            'alias'    => 'product'
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
     * Get the user record identifier,
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Get the product record identifier.
     *
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * Set the user record identifier,
     *
     * @param int $value
     * @return void
     */
    public function setUserId(int $value): void
    {
        $this->user_id = $value;
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
     * Get or set whether this user product is a favorite.
     *
     * @param bool|null $state
     * @return bool
     */
    public function isFavorite(?bool $state = null): bool
    {
        if ($state !== null) {
            $this->favorite = (int)$state;
        }

        return (bool)$this->favorite;
    }

    /**
     * Get or set whether this user product is being ignored.
     *
     * @param bool|null $state
     * @return bool
     */
    public function isIgnored(?bool $state = null): bool
    {
        if ($state !== null) {
            $this->ignoring = (int)$state;
        }

        return (bool)$this->ignoring;
    }
}
