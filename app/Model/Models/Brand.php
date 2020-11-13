<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;

/**
 * @method static Brand[] find(array $parameters = null)
 * @method static Brand findFirst(array $parameters = null)
 * @method static Brand findFirstByName(string $name)
 * @method Simple|Product[] getProducts()
 */
class Brand extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var int|null
     */
    private ?int $id = null;

    /**
     * The brand name.
     * 
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->hasMany('id', Product::class, 'brand_id', [
            'reusable' => true,
            'alias' => 'products',
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
     * Get the brand name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the brand name.
     *
     * @param string $value
     * @return void
     */
    public function setName(string $value): void
    {
        $this->name = $value;
    }

    /**
     * Get the first matching or created brand by name.
     *
     * @param string $name
     * @return Brand
     */
    public static function getFirstOrCreateByName(string $name): Brand
    {
        $brand = Brand::findFirstByName($name);
        if ($brand === null) {
            $brand = new Brand;
            $brand->setName($name);
            $brand->save();
        }

        return $brand;
    }
}
