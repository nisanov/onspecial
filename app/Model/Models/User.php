<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * @property-read Simple|Birthday[] $birthdays
 *
 * @method static Simple|User[] find(array|string $parameters = null)
 * @method static User findFirst(array|string|int|null $parameters = null)
 * @method static User findFirstByEmail(string $email)
 */
class User extends Generic
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
     * The email address.
     *
     * @var string
     */
    public string $email = "";

    /**
     * The password.
     *
     * @var string
     */
    public string $password = "";

    /**
     * The number of specials to display per page.
     *
     * @var int
     */
    public int $specials_per_page = 250;

    /**
     * Whether to show seen specials.
     *
     * @var int
     */
    public int $show_seen = 1;

    /**
     * Whether to show favorite products.
     *
     * @var int
     */
    public int $show_favorites = 1;

    /**
     * Whether to show ignored products/brands.
     *
     * @var int
     */
    public int $show_ignored = 0;

    /**
     * The email verification timestamp.
     *
     * @var string|null
     */
    public ?string $email_verified_at = null;

    /**
     * The last authentication timestamp.
     *
     * @var string|null
     */
    public ?string $authenticated_at = null;

    /**
     * The last visited timestamp.
     *
     * @var string|null
     */
    public ?string $last_visited_at = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->hasMany('id', Birthday::class, 'user_id', [
            'reusable' => true,
            'alias' => 'birthdays',
        ]);

        $this->hasMany('id', UserProduct::class, 'user_id', [
            'reusable' => true,
            'alias' => 'userProducts',
        ]);

        $this->hasManyToMany('id', UserProduct::class, 'user_id', 'product_id', Product::class, 'id', [
            'reusable' => true,
            'alias' => 'products',
        ]);
    }
}
