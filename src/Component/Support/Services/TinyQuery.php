<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support\Services;

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

/**
 * A minimal PSR-friendly class that:
 *  Boots a short-init version of WordPress,
 *  Offers a minimal DB query method (`query`),
 *  Provides a simple router mechanism via FastRoute,
 *  Lets you define routes and dispatch them,
 */
class TinyQuery
{
    /**
     * @var null|\FastRoute\Dispatcher
     */
    private $dispatcher;

    /**
     * @var array
     */
    private array $queryResults;

    /**
     * @var bool
     */
    private bool $booted;

    public function __construct()
    {
        $this->queryResults = [];
        $this->booted = false;
    }

    /**
     * A tiny bootloader when using SHORTINIT.
     *
     * Checks if the `SHORTINIT` constant is defined and set to `true` to enable a minimal WordPress bootstrap.
     * Boots a minimal environment (shortinit).
     *
     * `SHORTINIT` is a predefined constant in WordPress that allows for a lightweight initialization of the environment.
     * By defining `SHORTINIT` as `true` before loading WordPress, themes, plugins, and many optional features
     * are bypassed, leading to significant performance improvements in specific use cases.
     *
     * Key Characteristics of `SHORTINIT`:
     * - **Performance Optimization:** By skipping non-essential components, the initialization process is faster.
     * - **Resource Efficiency:** Helps scripts that only require core WordPress features operate smoothly in environments
     *   with limited resources.
     * - **Specific Use Cases:** Suitable for tasks requiring limited WordPress functionality, such as direct database
     *   interactions or basic user handling, without the overhead of a full WordPress load.
     *
     * When `SHORTINIT` is set to `true`:
     * - **Skipped Features:**
     *   - Themes and plugins
     *   - Widgets and shortcodes
     *   - REST API
     *   - Localization and translation functions
     * - **Retained Features:**
     *   - Core settings
     *   - The `$wpdb` object for database interactions
     *   - Basic functionality for a minimal WordPress bootstrap
     *
     * Example Use Cases:
     * - Direct database queries using `$wpdb`
     * - Custom scripts requiring authentication without loading the full WordPress stack
     *
     * @see https://wordpress.stackexchange.com/questions/28342/is-there-a-way-to-use-the-wordpress-users-but-without-loading-the-entire-wordpre/28347#28347
     * @see https://core.trac.wordpress.org/ticket/55489
     * @see  https://github.com/WordPress/wordpress-develop/blob/bcb3299a37712b61eb9b2a92c0b2fcc81e5d3d9d/src/wp-settings.php#L149
     */
    public function boot(?callable $callback = null): void
    {
        if ($this->booted) {
            return;
        }

        if (\defined('SHORTINIT') && true === \constant('SHORTINIT')) {
            \define('WPINC', 'wp-includes');

            require_once ABSPATH . WPINC . '/version.php';
            require_once ABSPATH . WPINC . '/load.php';
            require_once ABSPATH . WPINC . '/plugin.php';
            require_once ABSPATH . WPINC . '/compat.php';
            require_once ABSPATH . WPINC . '/class-wp-list-util.php';
            require_once ABSPATH . WPINC . '/formatting.php';
            require_once ABSPATH . WPINC . '/meta.php';
            require_once ABSPATH . WPINC . '/functions.php';
            require_once ABSPATH . WPINC . '/class-wp-meta-query.php';
            require_once ABSPATH . WPINC . '/class-wp-matchesmapregex.php';
            require_once ABSPATH . WPINC . '/class-wp.php';
            require_once ABSPATH . WPINC . '/class-wp-error.php';
            require_once ABSPATH . WPINC . '/pomo/mo.php';

            // Basic WP checks & object cache.
            wp_check_php_mysql_versions();
            wp_fix_server_vars();
            wp_start_object_cache();

            // Bootstrap the database.
            global $wpdb;
            $GLOBALS['table_prefix'] = env('DB_PREFIX');
            require_wp_db();
            wp_set_wpdb_vars();

            if ($callback && \is_callable($callback)) {
                $callback();
            }
        }

        $this->booted = true;
    }

    /**
     * Minimal function to query posts from wp_posts using $wpdb and prepared statements.
     *
     * @param string $output Optional. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
     *                       With one of the first three, return an array of rows indexed
     *                       from 0 by SQL result row number. Each row is an associative array
     *                       (column => value, ...), a numerically indexed array (0 => value, ...),
     *                       or an object ( ->column = value ), respectively. With OBJECT_K,
     *                       return an associative array of row objects keyed by the value
     *                       of each row's first column's value. Duplicate keys are discarded.
     *                       Default OBJECT.
     */
    public function query(array $args = [], $output = OBJECT): array
    {
        global $wpdb;

        $defaults = [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'name'           => '',
            'p'              => 0,
            'posts_per_page' => 1,
        ];
        $args = array_merge($defaults, $args);

        $where  = [];
        $params = [];

        $where[]  = 'post_type = %s';
        $params[] = $args['post_type'];

        $where[]  = 'post_status = %s';
        $params[] = $args['post_status'];

        if (!empty($args['name'])) {
            $where[]  = 'post_name = %s';
            $params[] = $args['name'];
        }

        if (!empty($args['p'])) {
            $where[]  = 'ID = %d';
            $params[] = (int) $args['p'];
        }

        $whereSql = implode(' AND ', $where);
        $sql      = "SELECT * FROM {$wpdb->posts} WHERE $whereSql LIMIT %d";
        $params[] = (int) $args['posts_per_page'];

        $prepared               = $wpdb->prepare($sql, $params);
        $results                = $wpdb->get_results($prepared, $output);
        $this->queryResults = $results;

        return $results;
    }

    /**
     * A minimal is_404() check. We treat "404" as "no posts found.".
     */
    public function is404(): bool
    {
        return empty($this->queryResults);
    }

    /**
     * Set route definitions for FastRoute.
     */
    public function setRoutes(array $routes): void
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes): void {
            foreach ($routes as $route) {
                [$method, $path, $handler] = $route;
                $r->addRoute($method, $path, $handler);
            }
        });
    }

    /**
     * Dispatch a given HTTP method and URI to the appropriate route
     * using the FastRoute dispatcher.
     *
     * Returns an array with the shape:
     *   [ 'status' => int, 'handler' => mixed, 'vars' => array ]
     * or
     *   [ 'status' => 404 ]
     *   [ 'status' => 405, 'allowed' => [ ... ] ]
     */
    public function dispatch(string $httpMethod, string $uri): array
    {
        if (!$this->dispatcher) {
            return ['status' => 500, 'error' => 'Dispatcher not set'];
        }

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return ['status' => 404];
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return ['status' => 405, 'allowed' => $routeInfo[1]];
            case \FastRoute\Dispatcher::FOUND:
                return [
                    'status'  => 200,
                    'handler' => $routeInfo[1],
                    'vars'    => $routeInfo[2],
                ];
        }

        return ['status' => 500];
    }
}
