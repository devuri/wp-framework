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
    protected ?string $routeSecret;

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
        $this->routeSecret = $configs->config['app']->get('health_status.secret', null);

        if (self::isHealthStatusCheck($isEnable, $statusRoute, $request)) {
            // critical urls
            $criticalRoutes = $configs->config['app']->get('health_status.critical', []);

            $status = $this->checkSystemStatus($criticalRoutes);

            $request = $request->withAttribute('healthStatus', $status)
                ->withAttribute('isRoute', true)
                ->withAttribute('responseHandled', true);

            return new JsonResponse($status, $status['code']);
        }

        return $handler->handle($request);
    }

    /**
     * Check a list of "critical" URLs to ensure they return a 200 response.
     */
    protected function checkCriticalUrls(array $criticalUrls = []): array
    {
        $urls = $criticalUrls;
        $appUrl = env('HOME_URL', '');

        if (empty($appUrl)) {
            return [
                'status'  => null,
                'message' => 'Malformed HOME_URL configuration.',
            ];
        }

        if (empty($urls)) {
            return [
                'status'  => true,
                'message' => 'No critical URLs configured.',
            ];
        }

        $details = [];
        $allOk   = true;

        foreach ($urls as $url) {
            $checkResult = $this->checkSingleUrl($appUrl . '/' . $url);
            $details[]   = $checkResult;

            if (false === $checkResult['status']) {
                $allOk = false;
            }
        }

        return [
            'status'  => $allOk,
            'message' => $allOk
                ? 'All critical URLs are reachable.'
                : 'One or more critical URLs failed.',
            'details' => $details,
        ];
    }

    private static function isHealthStatusCheck($isEnabled, $statusRoute, ServerRequestInterface $request): bool
    {
        if (!$isEnabled) {
            return false;
        }

        $requestPath = rtrim($request->getUri()->getPath(), '/');
        $expectedPath = rtrim("/{$statusRoute}", '/');

        return $requestPath === $expectedPath;
    }

    /**
     * Checks the system's health status.
     *
     * @return (bool|bool[]|string)[]
     *
     * @psalm-return array{healthy: bool, services: array{database: bool, cache: bool}, timestamp: false|string}
     */
    private function checkSystemStatus(array $criticalRoutes): array
    {
        $checks = [
            'database'      => $this->checkDatabaseConnection(),
            'home'          => $this->checkSingleUrl(env('HOME_URL', '')),
            'disk_space'    => $this->checkDiskSpace(),
            'memory_usage'  => $this->checkMemoryUsage(),
            'critical_urls' => $this->checkCriticalUrls($criticalRoutes),
            'cache'         => $this->checkCacheConnection(),
        ];

        // Determine overall health
        $overallStatus = true;
        foreach ($checks as $check) {
            if (false === $check['status']) {
                $overallStatus = false;

                break;
            }
        }

        // Determine overall health status
        $statusCode = $overallStatus ? 200 : 503;
        $healthy = (200 === $statusCode);

        return [
            'code' => $statusCode,
            'healthy' => $healthy,
            'status' => $overallStatus ? 'OK' : 'ERROR',
            'services' => $checks,
            'timestamp' => (new DateTime())->format(DATE_ATOM),
        ];
    }

    /**
     * Database connection health check.
     *
     * @return bool
     */
    private function checkDatabaseConnection(): array
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

            $status = true;
            $message = 'Database connection is healthy.';
        } catch (PDOException $e) {
            $status = false;
            $message = 'Database connection failed: ' . $e->getMessage();
            $databaseConnection = null;
        }

        if ($databaseConnection) {
            return ['status' => true, 'message' => $message];
        }

        return ['status' => $status, 'message' => $message];
    }

    /**
     * Cache connection health check.
     *
     * @return array
     */
    private function checkCacheConnection(): array
    {
        return ['status' => true, 'message' => 'Cache Connection is OK'];
    }

    private function checkDiskSpace(): array
    {
        $freeSpace  = disk_free_space('/');
        $totalSpace = disk_total_space('/');

        // Let’s say we consider less than 10% free space as failing
        $ratio = ($freeSpace / $totalSpace) * 100;
        $showRatio = $this->routeSecret ? $ratio : 'n/a';


        if ($ratio < 10) {
            return [
                'status'  => false,
                'message' => \sprintf('Disk free space is low (%.2f%% free).', $showRatio),
            ];
        }

        return [
            'status'  => true,
            'message' => \sprintf('Disk space is sufficient (%.2f%% free).', $showRatio),
        ];
    }

    /**
     * Check: Memory usage.
     */
    private function checkMemoryUsage(): array
    {
        $memoryLimit   = \ini_get('memory_limit');
        $limitInBytes  = $this->convertToBytes($memoryLimit);
        $currentUsage  = memory_get_usage(true);

        if (! $this->routeSecret) {
            $currentUsage = 'N/A';
        }


        // For demonstration, consider >90% usage as failing
        if ($currentUsage < $limitInBytes * 0.9) {
            return [
                'status'  => true,
                'message' => 'Memory usage is within acceptable limits.',
            ];
        }

        return [
            'status'  => false,
            'message' => \sprintf(
                'Memory usage is high: %s / %s',
                $this->humanReadableSize($currentUsage),
                $memoryLimit
            ),
        ];
    }

    /**
     * Use cURL (built into PHP) to check if a URL returns 200.
     */
    private function checkSingleUrl(string $url): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            // we only need the status code
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return [
                'status'  => false,
                'message' => \sprintf('Could not connect to %s: %s', $url, $error),
            ];
        }

        if (200 === $httpCode) {
            return [
                'status'  => true,
                'message' => \sprintf('%s returned HTTP 200 OK', $url),
            ];
        }

        return [
            'status'  => false,
            'message' => \sprintf('%s returned HTTP %d', $url, $httpCode),
        ];
    }

    /**
     * Convert shorthand memory size (e.g., "512M") to bytes.
     */
    private function convertToBytes(string $value): int
    {
        $value   = trim($value);
        $lastChar = strtolower($value[\strlen($value) - 1]);

        switch ($lastChar) {
            case 'g':
                return (int) $value * 1024 * 1024 * 1024;
            case 'm':
                return (int) $value * 1024 * 1024;
            case 'k':
                return (int) $value * 1024;
            default:
                return (int) $value;
        }
    }

    /**
     * Produce a human-readable size from bytes, e.g. "2.34 MB".
     */
    private function humanReadableSize(int $bytes, int $decimals = 2): string
    {
        $units  = ['B','KB','MB','GB','TB','PB','EB','ZB','YB'];
        $factor = (int) floor((\strlen($bytes) - 1) / 3);

        return \sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }
}
