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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpsOnlyMiddleware implements MiddlewareInterface
{
    /**
     * Process the incoming request and enforce HTTPS for specific routes.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();

		if( $this->isAdminRoute($request) && $uri->getScheme() !== 'https' ) {
			throw new \Exception('Access to this resource requires HTTPS.', 403);
		}

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
}
