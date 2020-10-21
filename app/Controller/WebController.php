<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Models\User;
use App\Service\Services\Authentication;
use App\Service\Services\Mailer;
use App\Service\Services\Redirector;
use Phalcon\Cache;
use Phalcon\Mvc\Controller as MvcController;

/**
 * @property Redirector $redirector
 * @property Authentication $authentication
 * @property Mailer $mailer
 * @property Cache $cache
 */
abstract class WebController extends MvcController
{
    /**
     * Get the authenticated user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->authentication->getUser();
    }

    /**
     * Determine whether an authenticated user exists.
     *
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->authentication->hasUser();
    }
}
