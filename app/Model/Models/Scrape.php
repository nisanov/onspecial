<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Carbon\Carbon;

/**
 * @method Store getStore()
 */
class Scrape extends Generic
{
    use HasTimestamps {
        stamp as parentStamp;
    }

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
     * The number of records scrapped.
     * 
     * @var int
     */
    private int $records = 0;

    /**
     * The number of records expired.
     * 
     * @var int
     */
    private int $expired = 0;

    /**
     * The number of seconds spent scrapping.
     * 
     * @var float
     */
    private float $seconds = 0.00;

    /**
     * The error information, as applicable.
     * 
     * @var string
     */
    private string $error = "";

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
     * Increment the number of records scrapped.
     *
     * @return void
     */
    public function incrementRecords(): void
    {
        $this->records++;
    }

    /**
     * Set the number of records scrapped.
     *
     * @param int $value
     * @return void
     */
    public function setRecords(int $value): void
    {
        $this->records = $value;
    }

    /**
     * Get the number of records scrapped.
     *
     * @return int
     */
    public function getRecords(): int
    {
        return $this->records;
    }

    /**
     * Set the number of records expired.
     *
     * @param int $value
     * @return void
     */
    public function setExpired(int $value): void
    {
        $this->expired = $value;
    }

    /**
     * Get the number of records expired.
     *
     * @return int
     */
    public function getExpired(): int
    {
        return $this->expired;
    }

    /**
     * Set the number of seconds spent scrapping.
     *
     * @param float $value
     * @return void
     */
    public function setSeconds(float $value): void
    {
        $this->seconds = $value;
    }

    /**
     * Get the number of seconds spent scrapping.
     *
     * @return float
     */
    public function getSeconds(): float
    {
        return $this->seconds;
    }

    /**
     * Set the error information.
     *
     * @param string $value
     * @return void
     */
    public function setError(string $value): void
    {
        $this->error = $value;
    }

    /**
     * Get the error information.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Update the created at and updated at timestamps accordingly.
     *
     * @return void
     */
    protected function stamp(): void
    {
        $this->parentStamp();

        if ($this->updated_at !== null) {
            $this->setSeconds($this->getUpdatedAt()->floatDiffInSeconds($this->getCreatedAt()));
        }
    }
}
