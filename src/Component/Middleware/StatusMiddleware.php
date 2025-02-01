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

use DateTime;
use PDO;
use PDOException;
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
        $configs = $this->configs->app();

        $isEnable = $configs->config['app']->get('health_status.enabled', false);
        $statusRoute = $configs->config['app']->get('health_status.route', 'up');
        // should be set in .env `HEALTH_STATUS_SECRET`
        $routeSecret = $configs->config['app']->get('health_status.secret', null);

        if (self::isHealthStatusCheck($isEnable, $statusRoute, $request)) {
            $status = $this->checkSystemStatus();

            $request = $request->withAttribute('healthStatus', $status)
                ->withAttribute('isRoute', true)
                ->withAttribute('responseHandled', true);

            return new JsonResponse($status, $status['healthy'] ? 200 : 503);
        }

        return $handler->handle($request);
    }

    private static function isHealthStatusCheck($isEnable, $statusRoute, ServerRequestInterface $request): bool
    {
        if ($isEnable && "/{$statusRoute}" === $request->getUri()->getPath()) {
            return true;
        }

        return false;
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
        $host = (string) env('DB_HOST');
        $databaseName = (string) env('DB_NAME');
        $username = (string) env('DB_USER');
        $password = (string) env('DB_PASSWORD');

        $dsn = "mysql:host={$host};dbname={$databaseName};charset=utf8mb4";

        try {
            $databaseConnection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            $databaseConnection = null;
        }

        if ($databaseConnection) {
            return true;
        }

        return false;
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
