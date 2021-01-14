<?php

declare(strict_types=1);

namespace App\Model\Traits;

use Carbon\Carbon;

trait HasTimestamps
{
    /**
     * The creation timestamp,
     * 
     * @var string|null
     */
    public ?string $created_at = null;

    /**
     * The modification timestamp,
     * 
     * @var string|null
     */
    public ?string $updated_at = null;

    /**
     * Update the created at and updated at timestamps accordingly.
     *
     * @return void
     */
    protected function stamp(): void
    {
        if ($this->created_at === null) {
            $this->created_at = Carbon::now()->toDateTimeString('microseconds');
        } else {
            $this->updated_at = Carbon::now()->toDateTimeString('microseconds');
        }
    }
}
