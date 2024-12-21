<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support\Services;

use PDOException;
use WPframework\Support\DBFactory;

class AuthValidator
{
    private string $authKey;
    private string $authSalt;
    private string $secureAuthKey;
    private string $secureAuthSalt;
    private object $wpUser;

    public function __construct(
        string $authKey,
        string $authSalt,
        string $secureAuthKey,
        string $secureAuthSalt
    ) {
        $this->authKey = $authKey;
        $this->authSalt = $authSalt;
        $this->secureAuthKey = $secureAuthKey;
        $this->secureAuthSalt = $secureAuthSalt;

        // users
        $this->wpUser = DBFactory::create('users');
    }

    /**
     * Validate the authentication cookie.
     *
     * @param string $cookie The cookie string in the format "username|expiration|hmac".
     * @param string $scheme Either 'auth' or 'secure_auth'.
     *
     * @return array
     */
    public function validate(string $cookie, bool $verifyHash = true, string $scheme = 'auth'): array
    {
        $schemeKey = $this->getKeyForScheme($scheme);
        if ( ! $schemeKey) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Invalid Scheme',
            ];
        }

        $cookieElements = explode('|', $cookie);
        if (\count($cookieElements) < 3) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Malformed cookie',
            ];
        }

        [$username, $expiration, $token, $hmac] = $cookieElements;

        if ((int) $expiration < time()) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Expired',
            ];
        }

        $user = $this->getUser($username);
        if ( ! $user) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Invlaid User',
            ];
        }

        if ( ! $verifyHash) {
            return [
                'user' => $user,
                'auth' => true,
                'message' => 'Logged In',
            ];
        }

        // get user pass fragment.
        $passFrag = substr($user->user_pass, 8, 4);
        $hashKey = self::getHashKey($username, $passFrag, $expiration, $token, $schemeKey);
        $calculatedHmac = hash_hmac('sha256', $username . '|' . $expiration . '|' . $token, $hashKey);

        if ( ! hash_equals($calculatedHmac, $hmac)) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Invalid HMAC',
            ];
        }

        return [
            'user' => $user,
            'auth' => true,
            'message' => 'OK',
        ];
    }

    public function getUser(string $username)
    {
        try {
            return $this->wpUser->getUser($username);
        } catch (PDOException $e) {
            return null;
        }
    }

    private static function getHashKey(string $username, string $passFrag, string $expiration, string $token, string $schemeKey)
    {
        return hash_hmac('md5', $username . '|' . $passFrag . '|' . $expiration . '|' . $token, $schemeKey);
    }

    /**
     * Get the key for the specified scheme.
     *
     * @param string $scheme
     *
     * @return null|string
     */
    private function getKeyForScheme(string $scheme): ?string
    {
        $keyScheme = [
            'auth' => $this->authKey . $this->authSalt,
            'secure_auth' => $this->secureAuthKey . $this->secureAuthSalt,
        ];

        return $keyScheme[$scheme] ?? null;
    }
}
