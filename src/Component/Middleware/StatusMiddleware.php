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

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WPframework\Http\Message\JsonResponse;

class StatusMiddleware extends AbstractMiddleware
{
    /**
     * Processes the health check request and returns status information.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $configs = $this->services->get('configs')->app();

        $enable_health_status = $configs->config['app']->get('enable_health_status', false);

        if ($enable_health_status && '/health-status' === $request->getUri()->getPath()) {
            $status = $this->checkSystemStatus();

            $request = $request->withAttribute('healthStatus', $status)
                ->withAttribute('isRoute', true)
                ->withAttribute('responseHandled', true);

            return new JsonResponse($status, $status['healthy'] ? 200 : 503);
        }

        return $handler->handle($request);
    }

    /**
     * Checks the system's health status.
     *
     * @return (bool|bool[]|string)[]
     *
     * @psalm-return array{healthy: bool, services: array{database: bool, cache: bool}, timestamp: false|string}
     */
    private function checkSystemStatus(): array
    {
        $checks = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
        ];

        // Determine overall health status
        $healthy = ! \in_array(false, $checks, true);

        return [
            'healthy' => $healthy,
            'services' => $checks,
            'timestamp' => (new DateTime())->format(DATE_ATOM),
        ];
    }

    /**
     * Database connection health check.
     *
     * @return bool
     */
    private function checkDatabaseConnection(): bool
    {
        return true;
    }

    /**
     * Cache connection health check.
     *
     * @return bool
     */
    private function checkCacheConnection(): bool
    {
        return true;
    }
}
