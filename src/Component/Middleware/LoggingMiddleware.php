<?php

declare(strict_types=1);

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
use Psr\Http\Server\RequestHandlerInterface;

class LoggingMiddleware extends AbstractMiddleware
{
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
        // $this->logger->info('Incoming request', [
        //     'method' => $request->getMethod(),
        //     'uri' => (string) $request->getUri(),
        //     'headers' => $request->getHeaders(),
        // ]);

        return $handler->handle($request);
    }
}
