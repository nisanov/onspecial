<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;

/**
 * @method static \App\Model\Models\UserProduct[] find(array $parameters = null)
 * @method static \App\Model\Models\UserProduct findFirst(array $parameters = null)
 * @method static \App\Model\Models\UserProduct findFirstByName(string $name)
 * @property-read \App\Model\Models\User $user
 * @property-read \App\Model\Models\Product $product
 */
class UserProduct extends Generic
{
    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The user record identifier.
     * 
     * @var int
     */
    public ?int $user_id = null;

    /**
     * The product record identifier.
     * 
     * @var int
     */
    public ?int $product_id = null;

    /**
     * Whether the product is a favorite.
     * 
     * @var int
     */
    public int $favorite = 0;

    /**
     * Whether the product is ignored.
     * 
     * @var int
     */
    public int $ignoring = 0;

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
}
