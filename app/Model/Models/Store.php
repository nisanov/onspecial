<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @method Simple getProducts()
 * @method Simple getSpecials()
 */
class Store extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     * 
     * @var string|null
     */
    private ?string $id = null;

    /**
     * The store name.
     * 
     * @var string
     */
    private string $name = "";

    /**
     * The active state.
     * 
     * @var string
     */
    private bool $active = true;

    /**
     * The scrapper class.
     * 
     * @var string
     */
    private string $scrapper;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->hasMany('id', Product::class, 'store_id', [
            'reusable' => true,
            'alias' => 'products',
        ]);

        $this->hasMany('id', Special::class, 'store_id', [
            'reusable' => true,
            'alias' => 'specials',
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
     * Set the store name.
     *
     * @param string $value
     * @return void
     */
    public function setName(string $value): void
    {
        $this->name = $value;
    }

    /**
     * Get the store name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get or set the active state.
     *
     * @param bool|null $state
     * @return bool
     */
    public function isActive(?bool $state = null): bool
    {
        if ($state !== null) {
            $this->active = $state;
        }

        return $this->active;
    }

    /**
     * Set the scrapper class.
     *
     * @param string $class
     * @return void
     */
    public function setScrapper(string $class): void
    {
        $this->scrapper = $class;
    }

    /**
     * Get the scrapper class.
     *
     * @return string
     */
    public function getScrapper(): string
    {
        return $this->scrapper;
    }

    /**
     * Get the first matching or created store by name.
     *
     * @param string $name
     * @param array $assignment
     * @return self
     */
    public static function getFirstOrCreatedByName(string $name, array $assignment = []): self
    {
        $store = self::findFirstByName($name);
        if ($store === null) {
            $store = new self;
            $store->setName($name);
            $store->assign($assignment);
            $store->save();
        }

        return $store;
    }
}
