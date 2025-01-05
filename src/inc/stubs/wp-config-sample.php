<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use WPframework\AppFactory;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 */
if (file_exists(\dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once \dirname(__DIR__) . '/vendor/autoload.php';
} else {
    exit('Cannot find the vendor autoload file.');
}

/*
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
\define('WP_DEBUG', false);

$siteAppFactory = AppFactory::create(\dirname(__DIR__));

// customHeaderMiddleware($siteAppFactory);

// That's all, stop editing! Happy publishing.

AppFactory::run();

// Set the table prefix.
$table_prefix = env('DB_PREFIX');

// Define ABSPATH.
if (! \defined('ABSPATH')) {
    \define('ABSPATH', __DIR__ . '/');
}

// Load WordPress settings
require_once ABSPATH . 'wp-settings.php';
