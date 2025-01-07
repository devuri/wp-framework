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

class AdminerMiddleware extends AbstractMiddleware
{
    /**
     * Process the incoming request and enforce HTTPS for specific routes.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $configs = $this->services->get('configs');
        $dbadmin = $configs->config['app']->get('dbadmin');
        $dbadminUrlPath = $dbadmin['uri'] ? '/wp/wp-admin/' . $dbadmin['uri'] : '/wp-admin/dbadmin';

        if (! $dbadmin['enabled']) {
            return $handler->handle($request);
        }

        $uriPath = $request->getUri()->getPath();
        $uri = $request->getUri();
        $userAuth = $request->getAttribute('authCheck', false);

        // Only users in the `kiosk` list with the `manage_database` or admin role
        $isAdmin = $request->getAttribute('isAdmin', false);

        if ($this->isAdminRoute($request) && $dbadmin['validate'] && ! $userAuth) {
            throw new Exception("Authentication Is Required", 401);
        }

        if (self::matchPaths($uriPath, $dbadminUrlPath) && $isAdmin) {
            require \dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'inc/configs/dbadmin/index.php';
            exit;
        }

        return $handler->handle($request);
    }

    /**
     * Matches two URL paths, considering optional trailing slashes.
     *
     * @param string $uriPath
     * @param string $dbadminUrlPath
     *
     * @return bool
     */
    public static function matchPaths(string $uriPath, string $dbadminUrlPath): bool
    {
        $normalizedUriPath = rtrim($uriPath, '/');
        $normalizedDbAdminUrlPath = rtrim($dbadminUrlPath, '/');

        return $normalizedUriPath === $normalizedDbAdminUrlPath;
    }
}
