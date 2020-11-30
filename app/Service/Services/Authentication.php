<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Form\Elements\Checkbox;
use App\Form\Forms\LoginForm;
use App\Model\Models\User;
use App\Service\Generic;
use Carbon\Carbon;

class Authentication extends Generic
{
    /**
     * The authenticated user.
     * 
     * @var User
     */
    protected ?User $user = null;

    /**
     * Process authentication via the Login Form.
     *
     * @param LoginForm $form
     * @return bool
     */
    public function viaForm(LoginForm $form): bool
    {
        $data = $this->request->getPost();
        if (!$form->isValid($data)) {
            return false;
        }

        $this->user = User::findFirstByEmail($data['email']);
        if ($this->user === null) {
            $form->setSessionStoredData($data, ['email' => "The email must exist on record"]);
            return false;
        }

        if ($this->user->getEmailVerifiedAt() === null) {
            $form->setSessionStoredData($data, ['email' => "The email has not been activated"]);
            return false;
        }

        if (!$this->security->checkHash($data['password'], $this->user->password)) {
            $form->setSessionStoredData($data, ['password' => "The password must be associated to the email"]);
            return false;
        }

        if ($data['remember'] === Checkbox::YES) {
            $this->rememberUser($this->user);
        }

        $this->authenticateUser($this->user);

        return true;
    }

    /**
     * Process authentication via the remember me token store in cookies.
     *
     * @return bool
     */
    public function viaRememberMeToken(): bool
    {
        if (!$this->cookies->has('RMT') || !$this->cookies->has('RMU')) {
            return false;
        }

        $this->user = User::findFirst($this->cookies->get('RMU')->getValue());
        if (!$this->user instanceof User) {
            return false;
        }

        $token = $this->cookies->get('RMT')->getValue();
        if ($token !== $this->getRememberToken($this->user)) {
            return false;
        }

        if (!$this->cache->has($token)) {
            return false;
        }

        if ($this->cache->get($token) !== $this->user->getId()) {
            return false;
        }

        $this->rememberUser($this->user);
        $this->authenticateUser($this->user);

        return true;
    }

    /**
     * Remember this users authentication via cookies.
     *
     * @param User $user
     * @return void
     */
    public function rememberUser(User $user): void
    {
        $token = $this->getRememberToken($user);
        $expires = Carbon::now()->addWeek()->timestamp;

        if ($this->cache->set($token, $user->getId(), $expires) !== false) {
            $this->cookies->set('RMU', $user->getId(), $expires);
            $this->cookies->set('RMT', $token, $expires);
        }
    }

    /**
     * Authenticate the specified user.
     *
     * @param User $user
     * @return void
     */
    public function authenticateUser(User $user): void
    {
        $this->user = $user;

        $this->user->setAuthenticatedAt(Carbon::now());
        $this->user->save();

        $this->session->set('auth-identity', [
            'id' => $this->user->getId(),
            'name' => $this->user->getName(),
        ]);
    }

    /**
     * Determine whether the authenticated user has a remember me token.
     *
     * @return bool
     */
    public function hasRememberMeToken(): bool
    {
        return $this->cookies->has('RMT');
    }

    /**
     * Get the remember token for the specified user.
     *
     * @param User $user
     * @return string
     */
    public function getRememberToken(User $user): string
    {
        $parts = [
            $user->getEmail(),
            $user->getPassword(),
            $this->request->getUserAgent(),
            $this->request->getClientAddress(),
        ];

        return md5(implode('', $parts));
    }

    /**
     * Remove the authenticated user from the session.
     *
     * @return void
     */
    public function remove(): void
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();
            $this->cookies->get('RMT')->delete();
            if ($this->cache->has($token)) {
                $this->cache->delete($token);
            }
        }

        $this->user = null;
        $this->session->remove('auth-identity');
    }

    /**
     * Get the authenticated user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user instanceof User) {
            return $this->user;
        }

        $identify = $this->session->get('auth-identity');
        if ($identify === null) {
            if ($this->hasRememberMeToken()) {
                if ($this->viaRememberMeToken()) {
                    return $this->user;
                }
            }
            return null;
        }

        $this->user = User::findFirst($identify['id']);
        if ($this->user === null) {
            return null;
        }

        return $this->user;
    }

    /**
     * Determine whether an authenticated user exists.
     *
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->user instanceof User;
    }
}
