<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;

/**
 * @property-read User $user
 * @property-read Brand $brand
 *
 * @method static UserBrand[] find(array|string $parameters = null)
 * @method static UserBrand findFirst(array|string|int|null $parameters = null)
 * @method static UserBrand findFirstByName(string $name)
 */
class UserBrand extends Generic
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
     * @var int|null
     */
    public ?int $user_id = null;

    /**
     * The brand record identifier.
     *
     * @var int|null
     */
    public ?int $brand_id = null;

    /**
     * Whether the brand is ignored.
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

        $this->setSource('user_brand');

        $this->belongsTo('user_id', User::class, 'id', [
            'reusable' => true,
            'alias' => 'user'
        ]);

        $this->belongsTo('brand_id', Brand::class, 'id', [
            'reusable' => true,
            'alias' => 'brand'
        ]);
    }
}
