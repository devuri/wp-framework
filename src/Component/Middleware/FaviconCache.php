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

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FaviconCache extends AbstractMiddleware
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * Constructor to inject the response factory.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->isFaviconRequest($request)) {
            return $this->handleFaviconRequest();
        }

        $this->when();

        return $handler->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function isFaviconRequest(ServerRequestInterface $request): bool
    {
        return false !== strpos($request->getUri()->getPath(), 'favicon.ico');
    }

    /**
     * @return ResponseInterface
     */
    private function handleFaviconRequest(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        if (\defined('FAVICON_ENABLE_CACHE') && FAVICON_ENABLE_CACHE === true) {
            $response = $this->sendCacheHeaders($response);
        }
        $responseType = \defined('FAVICON_RESPONSE_TYPE') ? FAVICON_RESPONSE_TYPE : 204;

        if (204 == $responseType) {
            return $response->withStatus(204);
        }
        if (404 == $responseType) {
            return $response->withStatus(404);
        }

        return $response;
    }

    /**
     * Send cache headers if caching is enabled.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    private function sendCacheHeaders(ResponseInterface $response): ResponseInterface
    {
        $cacheTime = \defined('FAVICON_CACHE_TIME') ? FAVICON_CACHE_TIME : 3600; // Default 1 hour cache

        return $response
            ->withHeader('Cache-Control', "public, max-age=$cacheTime")
            ->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + $cacheTime) . ' GMT');
    }
}
