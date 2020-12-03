<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;

/**
 * @method static \App\Model\Models\UserBrand[] find(array $parameters = null)
 * @method static \App\Model\Models\UserBrand findFirst(array $parameters = null)
 * @method static \App\Model\Models\UserBrand findFirstByName(string $name)
 * @method \App\Model\Models\User getUser()
 * @method \App\Model\Models\Brand getBrand()
 */
class UserBrand extends Generic
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
     * The brand record identifier.
     * 
     * @var int
     */
    private ?int $brand_id = null;

    /**
     * Whether the brand is ignored.
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

        $this->setSource('user_brand');

        $this->belongsTo('user_id', User::class, 'id', [
            'reusable' => true,
            'alias'    => 'user'
        ]);

        $this->belongsTo('brand_id', Brand::class, 'id', [
            'reusable' => true,
            'alias'    => 'brand'
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
     * Get the brand record identifier.
     *
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
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
     * Set the brand record identifier.
     *
     * @param int $value
     * @return void
     */
    public function setBrandId(int $value): void
    {
        $this->brand_id = $value;
    }

    /**
     * Get or set whether this user brand is being ignored.
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
