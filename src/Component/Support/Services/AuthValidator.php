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
    private array $sessionTokens;
    private object $wpUser;
    private object $userMeta;

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
        $this->userMeta = DBFactory::init('usermeta');
    }

    /**
     * Validate the authentication cookie.
     *
     * @param string $cookie The cookie string in the format "username|expiration|hmac".
     * @param string $scheme Either 'auth' or 'secure_auth'.
     *
     * @return (null|bool|mixed|string)[]
     *
     * @psalm-return array{user: mixed|null, auth: bool, message: string}
     */
    public function validate(string $cookie, bool $verifyHash = true, string $scheme = 'auth'): array
    {
        $schemeKey = $this->getKeyForScheme($scheme);
        if (! $schemeKey) {
            // error_log('Invalid Scheme');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        $cookieElements = explode('|', $cookie);
        if (\count($cookieElements) < 3) {
            // error_log('Malformed cookie');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        [$username, $expiration, $token, $hmac] = $cookieElements;

        if ((int) $expiration < time()) {
            // error_log('Expired');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        $user = $this->getUser($username);
        $this->sessionTokens = $this->userMeta->getUserMeta($user->ID, 'session_tokens');

        if (empty($this->sessionTokens)) {
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        if (! $this->isValidExpiration((int) $expiration)) {
            // error_log('Invlaid Expired Value');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        if (! $user) {
            // error_log('Invlaid User');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
            ];
        }

        if (! $verifyHash) {
            // error_log('Logged In');
            return [
                'user' => $user,
                'auth' => true,
                'message' => self::getMessage('loggedin'),
            ];
        }

        // get user pass fragment.
        $passFrag = substr($user->user_pass, 8, 4);
        $hashKey = self::getHashKey($username, $passFrag, $expiration, $token, $schemeKey);
        $calculatedHmac = hash_hmac('sha256', $username . '|' . $expiration . '|' . $token, $hashKey);

        if (! hash_equals($calculatedHmac, $hmac)) {
            // error_log('Invalid HMAC');
            return [
                'user' => null,
                'auth' => false,
                'message' => self::getMessage('default'),
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

    private function isValidExpiration(int $cookieExpiration): bool
    {
        foreach ($this->sessionTokens as $key => $userMeta) {
            if ($cookieExpiration === ($userMeta['expiration'] ?? null)) {
                return true;
            }
        }

        return false;
    }

    private static function getMessage(string $key): string
    {
        $errors = [
            'invalid_scheme' => 'Invalid Scheme',
            'malformed_cookie' => 'Malformed cookie',
            'expired' => 'Expired',
            'invalid_expiration_value' => 'Invalid Expired Value',
            'invalid_user' => 'Invalid User',
            'loggedin' => 'Logged In',
            'invalid_hmac' => 'Invalid HMAC',
            'default' => 'Invalid Credentials',
        ];

        return $errors[$key] ?? $errors['default'];
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
