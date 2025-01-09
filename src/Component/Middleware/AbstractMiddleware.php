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

use Pimple\Psr11\Container as PsrContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @var PsrContainer
     */
    protected $services;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(?PsrContainer $serviceContainer = null)
    {
        $this->services = $serviceContainer;
        $this->logger = $this->services->get('logger');
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;

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

    /**
     * @return LoggerInterface
     */
    protected function log(): LoggerInterface
    {
        return $this->logger;
    }

    protected function when(): void
    {
        // $this->log()->info('middleware(' . time() . '): ' . static::class);
    }

    /**
     * Merges two multi-dimensional arrays recursively.
     *
     * This function will recursively merge the values of `$array2` into `$array1`.
     * If the same key exists in both arrays, and both corresponding values are arrays,
     * the values are recursively merged.
     * Otherwise, values from `$array2` will overwrite those in `$array1`.
     *
     * @param array $array1 The base array that will be merged into.
     * @param array $array2 The array with values to merge into `$array1`.
     *
     * @return array The merged array.
     */
    protected static function multiMerge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (isset($merged[$key]) && \is_array($merged[$key]) && \is_array($value)) {
                $merged[$key] = self::multiMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * @param mixed $composerConfig
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    protected static function isMultitenantApp($composerConfig): bool
    {
        return $composerConfig->get('extra.multitenant.is_active', false);
    }

    /**
     * Check if a given URL or route matches the WordPress admin route pattern.
     *
     * This method ensures that only specified paths within the wp-admin directory
     * are allowed based on configurations. This is crucial for security as it prevents
     * unauthorized access to sensitive WordPress admin functionalities.
     *
     * Context:
     * When the `restrict_wpadmin` configuration is enabled (disabled by default), all requests to paths
     * under `/wp/wp-admin` will be restricted with a 401 response unless explicitly allowed.
     * The `isAdminRouteRestricted` method determines whether a request matches restricted
     * wp-admin routes and handles the restriction logic upstream.
     *
     * Examples of wp-admin paths to consider:
     * - `wp-admin/admin-ajax.php`: Frequently used by plugins for AJAX requests, should generally be allowed.
     * - `wp-admin/theme-editor.php`: Sensitive path that should typically be restricted.
     * - `wp-admin/options.php`: Core settings path that requires strict access control.
     * - `wp-admin/admin-post.php`: A common custom plugin endpoint that may require special handling.
     *
     * Example Configuration:
     * 'restrict_wpadmin' => [
     *     'enabled' => true, // Enables or disables wp-admin restrictions (disabled by default)
     *     'secure' => false, // Enables stricter matching for wp-admin paths
     *     'allowed' => [
     *         'admin-ajax.php' // Paths allowed even when restrictions are enabled
     *     ]
     * ];
     *
     * @param ServerRequestInterface $request The server request instance.
     *
     * @return bool Returns true if the route matches a restricted wp-admin route, false otherwise.
     */
    protected function isAdminRouteRestricted(ServerRequestInterface $request): bool
    {
        $pattern = null;
        $allowedPaths = $this->getAllowedAccessPaths();

        if (true === $allowedPaths['secure']) {
            $pattern = '/\/wp(?:\/.*)?\/wp-admin\/.*$/';
        } elseif (!empty($allowedPaths['allowed'])) {
            // pattern to allow specified paths
            $pattern = '/^\/wp(?:\/.*)?\/wp-admin\/((?!' . implode('|', array_map('preg_quote', $allowedPaths['allowed'])) . ').*)$/';
        }

        return 1 === preg_match($pattern, $request->getUri()->getPath());
    }

    /**
     * Retrieve allowed access paths for wp-admin routes.
     *
     * This method fetches a list of allowed paths for the wp-admin directory based on configuration.
     * It is designed to ensure that critical functionality like AJAX handling can operate without
     * unnecessary restrictions while still securing other sensitive admin functionalities.
     *
     * Configuration behavior:
     * - `security.restrict_wpadmin.enabled`: Enables or disables the restriction mechanism (disabled by default).
     * - `security.restrict_wpadmin.secure`: If true, applies stricter matching for wp-admin paths.
     * - `security.restrict_wpadmin.allowed`: An array of specific paths to allow, e.g., `['admin-ajax.php']`.
     *
     * Example Usage:
     * - Allow `admin-ajax.php` for AJAX requests to ensure plugins function correctly.
     * - Restrict paths like `theme-editor.php` or custom plugin endpoints unless explicitly allowed.
     * - Ensure security while allowing flexibility for specific use cases.
     *
     * Example Configuration:
     * 'restrict_wpadmin' => [
     *     'enabled' => true,
     *     'secure' => false,
     *     'allowed' => [
     *         'admin-ajax.php'
     *     ]
     * ];
     *
     * @return null|array Returns an array of allowed paths if restriction is enabled, null otherwise.
     */
    protected function getAllowedAccessPaths(): ?array
    {
        $cfgs = $this->services->get('configs')->app();

        // Check if wp-admin restrictions are enabled
        $restrictWPadmin = $cfgs->config['app']->get('security.restrict_wpadmin.enabled', false);

        // Retrieve the list of allowed paths
        $allowedPaths = $cfgs->config['app']->get('security.restrict_wpadmin.allowed', []);
        $secure = $cfgs->config['app']->get('security.restrict_wpadmin.secure', false);

        // Return allowed paths and secure flag if restrictions are enabled
        if ($restrictWPadmin) {
            return [
                'allowed' => $allowedPaths,
                'secure' => $secure,
            ];
        }

        return null;
    }
}
