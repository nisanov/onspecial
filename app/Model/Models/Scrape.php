<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Carbon\Carbon;

/**
 * @method static \App\Model\Models\Scrape[] find(array $parameters = null)
 * @method static \App\Model\Models\Scrape findFirst(array $parameters = null)
 * @method \App\Model\Models\Store getStore()
 */
class Scrape extends Generic
{
    use HasTimestamps {
        stamp as parentStamp;
    }

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
     * The number of records scrapped.
     * 
     * @var int
     */
    public int $records = 0;

    /**
     * The number of records expired.
     * 
     * @var int
     */
    public int $expired = 0;

    /**
     * The number of seconds spent scrapping.
     * 
     * @var float
     */
    public float $seconds = 0.00;

    /**
     * The error information, as applicable.
     * 
     * @var string|null
     */
    public ?string $error = null;

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
     * Update the created at and updated at timestamps accordingly.
     *
     * @return void
     */
    protected function stamp(): void
    {
        $this->parentStamp();

        if ($this->updated_at !== null) {
            $this->seconds = Carbon::parse($this->updated_at)->floatDiffInSeconds(Carbon::parse($this->created_at));
        }
    }
}
