<?php

declare(strict_types=1);

namespace App\Model\Models;

use App\Model\Generic;
use App\Model\Traits\HasTimestamps;
use Carbon\Carbon;

/**
 * @method static Simple|User[] find(array $parameters = null)
 * @method static User findFirst(array $parameters = null)
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
    private ?int $id = null;

    /**
     * The full name.
     * 
     * @var string
     */
    private string $name = "";

    /**
     * The email address.
     * 
     * @var string
     */
    private string $email = "";

    /**
     * The password.
     * 
     * @var string
     */
    private string $password = "";

    /**
     * Whether to show seen specials.
     * 
     * @var int
     */
    private int $show_seen = 1;

    /**
     * Whether to show favorite products.
     * 
     * @var int
     */
    private int $show_favorites = 1;

    /**
     * Whether to show ignored products/brands.
     * 
     * @var int
     */
    private int $show_ignored = 0;

    /**
     * The email verification timestamp.
     * 
     * @var string|null
     */
    private ?string $email_verified_at = null;

    /**
     * The last authentication timestamp.
     * 
     * @var string|null
     */
    private ?string $authenticated_at = null;

    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->hasMany('id', UserProduct::class, 'user_id', [
            'reusable' => true,
            'alias' => 'userProducts',
        ]);

        $this->hasManyToMany('id', UserProduct::class, 'user_id', 'product_id', Product::class, 'id', [
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
     * Get the full name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get or set whether to show seen specials.
     *
     * @param bool $state
     * @return bool
     */
    public function isShowingSeen(bool $state = null): bool
    {
        if ($state !== null) {
            $this->show_seen = (int)$state;
        }

        return (bool)$this->show_seen;
    }

    /**
     * Get or set whether to show favorite products.
     *
     * @param bool $state
     * @return bool
     */
    public function isShowingFavorites(bool $state = null): bool
    {
        if ($state !== null) {
            $this->show_favorites = (int)$state;
        }

        return (bool)$this->show_favorites;
    }

    /**
     * Get or set whether to show ignored products/brands.
     *
     * @param bool $state
     * @return bool
     */
    public function isShowingIgnored(bool $state = null): bool
    {
        if ($state !== null) {
            $this->show_ignored = (int)$state;
        }

        return (bool)$this->show_ignored;
    }

    /**
     * Get the email verification timestamp.
     *
     * @return Carbon|null
     */
    public function getEmailVerifiedAt(): ?Carbon
    {
        if ($this->email_verified_at === null) {
            return null;
        }

        return Carbon::parse($this->email_verified_at);
    }

    /**
     * Get the last authentication timestamp.
     *
     * @return Carbon|null
     */
    public function getAuthenticatedAt(): ?Carbon
    {
        if ($this->authenticated_at === null) {
            return null;
        }

        return Carbon::parse($this->authenticated_at);
    }

    /**
     * Set the full name.
     *
     * @param string $value
     * @return void
     */
    public function setName(string $value): void
    {
        $this->name = $value;
    }

    /**
     * Set the email.
     *
     * @param string $value
     * @return void
     */
    public function setEmail(string $value): void
    {
        $this->email = $value;
    }

    /**
     * Set the password.
     *
     * @param string $value
     * @return void
     */
    public function setPassword(string $value): void
    {
        $this->password = $value;
    }

    /**
     * Set the email verification timestamp.
     *
     * @param Carbon $carbon
     * @return void
     */
    public function setEmailVerifiedAt(Carbon $carbon): void
    {
        $this->email_verified_at = $carbon->toDateTimeString();
    }

    /**
     * Set the authentication timestamp.
     *
     * @param Carbon $carbon
     * @return void
     */
    public function setAuthenticatedAt(Carbon $carbon): void
    {
        $this->authenticated_at = $carbon->toDateTimeString();
    }
}
