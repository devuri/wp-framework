<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support\Services;

use WPframework\Support\Configs;

class AuthManager
{
    protected $authValidator;
    protected $currentUser;
    private $cookies = [];
    private $configs;

    public function __construct(?AuthValidator $authValidator = null)
    {
        if ($authValidator) {
            $this->authValidator = $authValidator;
        }
    }

    public function setConfigs(Configs $configs): void
    {
        $this->configs = $configs;
    }

    public function setValidator(): void
    {
        $this->authValidator = new AuthValidator(
            env('AUTH_KEY'),
            env('AUTH_SALT'),
            env('SECURE_AUTH_KEY'),
            env('SECURE_AUTH_SALT'),
        );
    }

    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;
    }

    /**
     * Check if the current user is authenticated.
     *
     * @return bool
     */
    public function check(string $scheme = 'http'): ?bool
    {
        if (empty($this->cookies)) {
            return null;
        }

        $schemeKey = [
            'http' => 'auth',
            'https' => 'secure_auth',
        ];

        $cookie = null;
        $secureCookie = $this->getSecureAuthCookie();
        $authCookie = $this->getAuthCookie();
        $loginCookie = $this->getLoggedInCookie();

        if ($authCookie) {
            $cookie = $authCookie;
        } elseif ($loginCookie) {
            $cookie = $loginCookie;
        }

        if ('https' === $scheme) {
            $cookie = $secureCookie;
        }

        if (empty($cookie)) {
            return null;
        }

        $result = $this->authValidator->validate($cookie, true, $schemeKey[$scheme]);

        if (true === $result['auth']) {
            $this->currentUser = $result['user'];

            return true;
        }

        return false;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|array
     */
    public function user(): ?array
    {
        if (null !== $this->currentUser) {
            return $this->currentUser;
        }

        $this->check();

        return $this->currentUser;
    }

    /**
     * Determines if the current user is a kiosk admin.
     *
     * This method checks whether the current user has the 'admin' role. It first
     * verifies if the user is a kiosk user. If not, it returns `false`. Otherwise,
     * it checks if the kiosk user's role is 'admin'.
     *
     * @return bool Returns `true` if the user is an admin, `false` if not a kiosk user.
     */
    public function isSuperAdmin(): bool
    {
        $kioskUser = $this->isKioskUser();

        if (! $kioskUser) {
            return false;
        }

        return ($kioskUser['role'] ?? null) === 'superadmin';
    }

    public function userCan(string $capability = 'manage_kiosk'): bool
    {
        $kioskUser = $this->isKioskUser();

        if (! $kioskUser) {
            return false;
        }

        $permissions = $this->configs->config['kiosk']->get(
            "panel.users.{$this->currentUser->user_login}.permissions",
            null
        );

        return \in_array($capability, $permissions, true);
    }

    /**
     * @return null|array|false
     */
    public function isKioskUser()
    {
        if (! $this->currentUser) {
            return false;
        }

        return $this->getKioskUser($this->currentUser->user_login);
    }

    protected function getKioskUser(string $username): ?array
    {
        return $this->configs->config['kiosk']->get("panel.users.{$username}", null);
    }

    /**
     * Get the authentication cookie from the request.
     *
     * @return null|string
     */
    protected function getAuthCookie(): ?string
    {
        return $this->getCookie(AUTH_COOKIE);
    }

    /**
     * Get the secure cookie from the request.
     *
     * @return null|string
     */
    protected function getSecureAuthCookie(): ?string
    {
        return $this->getCookie(SECURE_AUTH_COOKIE);
    }

    protected function getLoggedInCookie(): ?string
    {
        return $this->getCookie(LOGGED_IN_COOKIE);
    }

    private function getCookie(string $key): ?string
    {
        if (empty($this->cookies)) {
            return null;
        }

        return $this->cookies[$key] ?? null;
    }
}
