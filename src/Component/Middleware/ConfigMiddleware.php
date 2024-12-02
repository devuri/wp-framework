<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Config;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\Services\AuthValidator;
use WPframework\Support\SiteManager;
use WPframework\Support\Switcher;

class ConfigMiddleware extends AbstractMiddleware
{
    private $configManager;
    private $siteManager;

    /**
     * @param ConstantBuilder $configManager
     */
    public function __construct(ConstantBuilder $configManager)
    {
        $this->configManager = $configManager;
        $this->siteManager = new SiteManager($this->configManager);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->siteManager->setSwitcher(
            new Switcher($this->configManager)
        );

        $this->siteManager->appSetup($request)->constants();

        $this->configManager->setMap();

        $authCookie = $this->getAuthCookie($request);
        $loginCookie = $this->getLoginCookie($request);
        $userAuth = $this->authCheck($authCookie, $loginCookie);

        if ($this->isAdminRoute($request) && ! $userAuth['auth']) {
            if ( ! self::isInstallOrUpgrade()) {
                throw new Exception("Authentication Is Required");
            }

            if (self::isInstallOrUpgrade() && self::isInstallBlocked()) {
                throw new Exception("It seems you're performing a new installation or upgrade. Install protection is currently enabled, so you'll need to disable it to continue.");
            }
        }

        // TODO block admin routes with authCheck.

        $request = $request->withAttribute('isProd', $this->isProd())
            ->withAttribute('authCheck', $userAuth);

        return $handler->handle($request);
    }

    protected function authCheck(?string $authCookie = null, ?string $loginCookie = null)
    {
        if ($authCookie) {
            $cookie = $authCookie;
        } elseif ($loginCookie) {
            $cookie = $loginCookie;
        }

        if (empty($cookie)) {
            return [
                'user' => null,
                'auth' => false,
                'message' => 'Invalid',
            ];
        }

        $validator = new AuthValidator(
            env('AUTH_KEY'),
            env('AUTH_SALT'),
            env('SECURE_AUTH_KEY'),
            env('SECURE_AUTH_SALT'),
        );

        return $validator->validate($cookie);
    }

    protected function getAuthCookie(ServerRequestInterface $request)
    {
        return $this->getCookie('auth_cookie', $request);
    }

    protected function getLoginCookie(ServerRequestInterface $request)
    {
        return $this->getCookie('logged_in_cookie', $request);
    }

    /**
     * Check if a given URL or route matches the WordPress admin route pattern.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool Returns true if the route matches a wp-admin route, false otherwise.
     */
    protected function isAdminRoute(ServerRequestInterface $request): bool
    {
        $pattern = '/\/wp(?:\/.*)?\/wp-admin\/.*$/';

        return 1 === preg_match($pattern, $request->getUri()->getPath());
    }

    protected static function isInstallBlocked(): bool
    {
        return \defined('RAYDIUM_INSTALL_PROTECTION') && true === \constant('RAYDIUM_INSTALL_PROTECTION');
    }

    protected static function isInstallOrUpgrade(): bool
    {
        return \defined('WP_INSTALLING') && true === \constant('WP_INSTALLING');
    }

    private function isProd(): bool
    {
        return Config::isProd($this->siteManager->getEnvironment());
    }

    private function getCookie(string $key, ServerRequestInterface $request)
    {
        $cookiehash = md5(env('WP_HOME'));
        $reqCookies = $request->getCookieParams();

        $cookie = [
            'user_cookie' => 'wpc_user_' . $cookiehash,
            'pass_cookie' => 'wpc_pass_' . $cookiehash,
            'auth_cookie' => 'wpc_' . $cookiehash,
            'secure_auth_cookie' => 'wpc_sec_' . $cookiehash,
            'logged_in_cookie' => 'wpc_logged_in_' . $cookiehash,
        ];

        return $reqCookies[$cookie[$key]] ?? null;
    }
}
