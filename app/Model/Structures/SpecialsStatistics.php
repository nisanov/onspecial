<?php

declare(strict_types=1);

namespace App\Model\Structures;

use App\Model\Models\User;
use App\Repository\Repositories\ProductRepository;

class SpecialsStatistics
{
    /**
     * The number of specials that are currently available.
     *
     * @var int
     */
    public int $available;

    /**
     * The number of specials being listed in the view.
     *
     * @var int
     */
    public int $listed;

    /**
     * The number of specials being ignored by the user.
     *
     * @var int
     */
    public int $ignoring;

    /**
     * The number of specials being favorited by the user.
     *
     * @var int
     */
    public int $favoriting;

    /**
     * The number of pages the listed specials sprawl.
     *
     * @var int
     */
    public int $pages = 1;

    /**
     * The number of specials displayed per page.
     *
     * @var int
     */
    public int $perPage;

    /**
     * The current page of listed specials viewed.
     *
     * @var int
     */
    public int $page = 1;

    /**
     * The current page offset of listed specials viewed.
     *
     * @var int
     */
    public int $offset = 0;

    /**
     * Statistics constructor.
     *
     * @param User $user
     * @param int $listed
     * @return void
     */
    public function __construct(User $user, int $listed)
    {
        $statistics = (new ProductRepository())->getStatistics($user)->toArray();

        $this->listed = $listed;
        $this->perPage = $user->specials_per_page;

        $this->available = (int)$statistics['products'];
        $this->ignoring = (int)$statistics['ignoring'];
        $this->favoriting = (int)$statistics['favoriting'];
    }

    /**
     * Set the page number of listed specials viewed.
     *
     * @param int $number
     * @return $this
     */
    public function setPage(int $number): self
    {
        if ($this->listed) {
            $this->pages = (int)ceil($this->listed / $this->perPage);
            $this->page = ($number > $this->pages) ? $this->pages : $number;
            $this->offset = ($this->page - 1) * $this->perPage;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getAvailable(): int
    {
        return $this->available;
    }

    /**
     * @return int
     */
    public function getListed(): int
    {
        return $this->listed;
    }

    /**
     * @return int
     */
    public function getIgnoring(): int
    {
        return $this->ignoring;
    }

    /**
     * @return int
     */
    public function getFavoriting(): int
    {
        return $this->favoriting;
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
