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

class HttpsOnlyMiddleware extends AbstractMiddleware
{
    /**
     * Process the incoming request and enforce HTTPS for specific routes.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();

        if ($this->isAdminRouteRestricted($request) && 'https' !== $uri->getScheme()) {
            throw new Exception('Access to this resource requires HTTPS.', 403);
        }

        return $handler->handle($request);
    }
}
