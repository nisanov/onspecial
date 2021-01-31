<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Phalcon\Db\Exception as DbException;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property-read Simple|Product[] $products
 *
 * @method static Brand[] find(array|string $parameters = null)
 * @method static Brand findFirst(array|string|int|null $parameters = null)
 * @method static Brand findFirstByName(string $name)
 */
class Brand extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The brand name.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Get the first matching or created brand by name.
     *
     * @param string $name
     * @return Brand
     * @throws DbException
     */
    public static function getFirstOrCreatedByName(string $name): Brand
    {
        $brand = Brand::findFirstByName($name);
        if ($brand === null) {
            $brand = new Brand;
            $brand->name = $name;
            $brand->save();
        }

        return $brand;
    }

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
}
