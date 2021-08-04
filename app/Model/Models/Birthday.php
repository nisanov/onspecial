<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Carbon\Carbon;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property-read User $user
 *
 * @method static Simple|Birthday[] find(array|string $parameters = null)
 * @method static Birthday findFirst(array|string|int|null $parameters = null)
 * @method static Birthday findFirstByEmail(string $email)
 */
class Birthday extends Generic
{
    use HasTimestamps;

    /**
     * The unique record identifier.
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * The full name.
     *
     * @var string
     */
    public string $name = "";

    /**
     * The birthdate.
     *
     * @var string|null
     */
    public ?string $born = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->belongsTo('user_id', User::class, 'id', [
            'reusable' => true,
            'alias' => 'user'
        ]);
    }

    /**
     * Get the born date as a Carbon instance.
     *
     * @return Carbon
     */
    public function born(): Carbon
    {
        return Carbon::parse($this->born);
    }

    /**
     * Get the timespan for this birthday.
     *
     * @return string
     */
    public function timespan(): string
    {
        return $this->born()->ago(['join' => ', ', 'parts' => 3]);
    }

    /**
     * Get the upcoming birthday as a Carbon instance.
     *
     * @return Carbon
     */
    public function upcoming(): Carbon
    {
        $upcoming = $this->born()->year(Carbon::now()->year);

        return $upcoming->isPast() ? $upcoming->addYear() : $upcoming;
    }

    /**
     * Get the upcoming birthday age.
     *
     * @return int
     */
    public function upcomingAge(): int
    {
        return $this->born()->age + 1;
    }

    /**
     * Get the upcoming birthday age with ordinal suffix.
     *
     * @return string
     */
    public function upcomingAgeWithOrdinalSuffix(): string
    {
        $age = (string)$this->upcomingAge();

        switch (substr($age, -1)) {
            case '1':
                $ordinal = substr($age, -2) == '11' ? 'th' : 'st';
                break;
            case '2':
                $ordinal = substr($age, -2) == '12' ? 'th' : 'nd';
                break;
            case '3':
                $ordinal = substr($age, -2) == '13' ? 'th' : 'rd';
                break;
            default:
                $ordinal = 'th';
        }

        return "$age$ordinal";
    }
}
