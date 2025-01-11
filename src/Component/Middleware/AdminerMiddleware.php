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
     * Process the database administration panel request routes.
     *
     * Handles requests related to the database administration panel by validating user access
     * and serving the appropriate content or delegating further processing.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @throws Exception If authentication is required but not provided.
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $configs = $this->services->get('configs');
        $dbAdminConfig = $configs->config['app']->get('dbadmin');
        $adminerUri = env('ADMINER_URI', $dbAdminConfig['uri']);

        // Determine the database admin URL path
        $dbAdminUrlPath = $adminerUri
            ? '/wp/wp-admin/' . $adminerUri
            : '/wp-admin/dbadmin';

        // If database admin is disabled.
        if (!$dbAdminConfig['enabled'] || self::isSecureMode()) {
            return $handler->handle($request);
        }

        $uriPath = $request->getUri()->getPath();
        $isDbAdminRequest = self::matchPaths($uriPath, $dbAdminUrlPath);

        $isAuthenticated = $request->getAttribute('authCheck', false);
        $isSuperAdmin = $request->getAttribute('isSuperAdmin', false);

        // Validate authentication for database admin requests
        if ($isDbAdminRequest && $dbAdminConfig['validate'] && !$isAuthenticated) {
            throw new Exception("Authentication is required", 401);
        }

        // Serve the database admin page
        if ($isDbAdminRequest && $isSuperAdmin) {
            require \dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'inc/configs/dbadmin/index.php';
            exit;
        }

        return $handler->handle($request);
    }

    private static function isSecureMode(): bool
    {
        $environment = \defined('ENVIRONMENT_TYPE') ? ENVIRONMENT_TYPE : null;
        if (\in_array(env('ENVIRONMENT_TYPE'), ['sec', 'secure'], true) || \in_array($environment, ['sec', 'secure'], true)) {
            return true;
        }

        return false;
    }
}
