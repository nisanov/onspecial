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
    private ?string $created_at = null;

    /**
     * The modification timestamp,
     * 
     * @var string|null
     */
    private ?string $updated_at = null;

    /**
     * Update the created at and updated at timestamps accordingly.
     *
     * @return void
     */
    protected function stamp(): void
    {
        if ($this->created_at === null) {
            $this->setCreatedAt(Carbon::now());
        } else {
            $this->setUpdatedAt(Carbon::now());
        }
    }

    /**
     * Set the creation timestamp.
     *
     * @param Carbon $carbon
     * @return void
     */
    public function setCreatedAt(Carbon $carbon): void
    {
        $this->created_at = $carbon->format('Y-m-d H:i:s.u');
    }

    /**
     * Get the creation timestamp.
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * Set the modification timestamp.
     *
     * @param Carbon $carbon
     * @return void
     */
    public function setUpdatedAt(Carbon $carbon): void
    {
        $this->updated_at = $carbon->format('Y-m-d H:i:s.u');
    }

    /**
     * Get the modification timestamp.
     *
     * @return Carbon|null
     */
    public function getUpdatedAt(): ?Carbon
    {
        if ($this->updated_at === null) {
            return null;
        }

        return Carbon::parse($this->updated_at);
    }
}
