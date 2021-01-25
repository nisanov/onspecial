<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @property-read \App\Model\Models\Store $store
 * @property-read \App\Model\Models\Store $product
 * 
 * @method static \App\Model\Models\Special[] find(array $parameters = null)
 * @method static \App\Model\Models\Special findFirst(array $parameters = null)
 */
class Special extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The store record identifier.
     * 
     * @var int|null
     */
    public ?int $store_id = null;

    /**
     * The product record identifier.
     * 
     * @var int|null
     */
    public ?int $product_id = null;

    /**
     * The current price.
     * 
     * @var float
     */
    public float $current_price = 0.00;

    /**
     * The previous price.
     * 
     * @var float
     */
    public float $previous_price = 0.00;

    /**
     * The ratio price.
     * 
     * @var string|null
     */
    public ?string $ratio_price = null;

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
}
