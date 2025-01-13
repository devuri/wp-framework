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

class AuthMiddleware extends AbstractMiddleware
{
    private $auth;

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->auth = $this->services->get('auth');
        $cookies = $request->getCookieParams();
        $this->auth->setConfigs($this->configs);
        $this->auth->setValidator();
        $this->auth->setCookies($cookies);
        $userAuth = $this->auth->check($request->getUri()->getScheme());

        if ($this->isAdminRouteRestricted($request) && ! $userAuth) {
            if (! self::isInstallOrUpgrade()) {
                throw new Exception("Route Authentication Is Required", 401);
            }

            if (self::isInstallOrUpgrade() && self::isInstallBlocked()) {
                throw new Exception("It seems you're performing a new installation or upgrade. Install protection is currently enabled, so you'll need to disable it to continue.", 403);
            }
        }

        // TODO we can block admin routes with authCheck.
        $request = $request->withAttribute('authCheck', $userAuth)->withAttribute('isSuperAdmin', $this->auth->isSuperAdmin());

        return $handler->handle($request);
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
