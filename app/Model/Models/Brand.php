<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @method static Brand findFirstByName(string $name)
 * @method Simple getProducts()
 */
class Brand extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var string|null
     */
    private ?string $id = null;

    /**
     * The brand name.
     * 
     * @var string
     */
    private string $name = "";

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
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * Get the brand name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the first matching or created brand by name.
     *
     * @param string $name
     * @return Brand
     */
    public static function getFirstOrCreatedByName(string $name): Brand
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
