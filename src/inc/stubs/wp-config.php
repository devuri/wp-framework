<?php

use WPframework\AppFactory;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 */
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    exit('Cannot find the vendor autoload file.');
}

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', false);


/* That's all, stop editing! Happy publishing. */

$siteAppFactory = AppFactory::create(dirname(__DIR__));
AppFactory::run();

// Set the table prefix.
$table_prefix = env('DB_PREFIX');

//  if hybridx is running do not load wp.
if (defined('HYBRIDX') && true === constant('HYBRIDX')) {
    return null;
}

// Define ABSPATH.
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

// Load WordPress settings
require_once ABSPATH . 'wp-settings.php';


/**
 * Checks if the `SHORTINIT` constant is defined and set to `true` to enable a minimal WordPress bootstrap.
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
if (defined('SHORTINIT') && true === constant('SHORTINIT')) {
    // Handle your specific use case and load only the necessary parts of WordPress.
}
