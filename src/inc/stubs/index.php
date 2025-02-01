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

\define('WP_USE_THEMES', true);

/*
 * Define lightweight framework mode.
 *
 * When `HYBRIDX` is set to true, the framework operates in a lightweight mode
 * and avoids fully loading WordPress, instead relying on `wp-config.php` for dependency
 * bootstrapping. This is useful for specific scenarios where we only a minimal framework environment.
 */
\define('HYBRIDX', false);

/*
 * Bootstrap the application.
 *
 * This logic determines the initialization process:
 * - If `HYBRIDX` is defined and set to true, then we load `wp-config.php` to bootstrap
 *   the application in lightweight mode.
 * - Otherwise, it attempts to load and initialize WordPress normally.
 *
 * @throws RuntimeException If neither the framework nor WordPress can be initialized.
 */
if (\defined('HYBRIDX') && true === \constant('HYBRIDX')) {
    $initializationConfigPath = __DIR__ . '/wp-config.php';
    if (file_exists($initializationConfigPath)) {
        require $initializationConfigPath;
    } else {
        exit("Error: initialization file wp-config.php not found. Please check your configuration.");
    }
} elseif (file_exists(__DIR__ . '/wp/wp-blog-header.php')) {
    require __DIR__ . '/wp/wp-blog-header.php';
} else {
    exit("Error: Framework setup incomplete. Run setup or 'composer install' to proceed.");
}
