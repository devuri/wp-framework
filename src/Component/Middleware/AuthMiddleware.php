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
use WPframework\Support\Services\AuthManager;

class AuthMiddleware extends AbstractMiddleware
{
    private $auth;

    public function __construct(AuthManager $authManager)
    {
        $this->auth = $authManager;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookies = $request->getCookieParams();
        $this->auth->setValidator();
        $this->auth->setCookies($cookies);
        $userAuth = $this->auth->check();

        if ($this->isAdminRoute($request) && ! $userAuth) {
            if ( ! self::isInstallOrUpgrade()) {
                throw new Exception("Authentication Is Required");
            }

            if (self::isInstallOrUpgrade() && self::isInstallBlocked()) {
                throw new Exception("It seems you're performing a new installation or upgrade. Install protection is currently enabled, so you'll need to disable it to continue.");
            }
        }

        // TODO we can block admin routes with authCheck.
        $request = $request->withAttribute('authCheck', $userAuth);

        return $handler->handle($request);
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
}
