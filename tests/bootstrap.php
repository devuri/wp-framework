<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once \dirname(__FILE__, 2) . '/vendor/autoload.php';

// app test path
\define('APP_SRC_PATH', \dirname(__FILE__, 2) . '/src');
\define('APP_TEST_PATH', __DIR__);
\define('ABSPATH', __DIR__);
\define('WEBAPP_ENCRYPTION_KEY', APP_TEST_PATH . '/.secret.txt');
\define('IS_MULTITENANT', false);


if ( ! \defined('SITE_CONFIGS_DIR')) {
    \define('SITE_CONFIGS_DIR', 'configs');
}

if ( ! \defined('APP_HTTP_HOST')) {
    \define('APP_HTTP_HOST', 'example.com');
}

if ( ! \defined('APP_DIR_PATH')) {
    \define('APP_DIR_PATH', __DIR__);
}

// true to run unit tests.
\define('WP_ENV_TEST_MODE', true);

// github actions environment variables.
\define('CORE_GITHUB_EVENT_NAME', getenv('GITHUB_EVENT_NAME'));
\define('CORE_GITHUB_REF', getenv('GITHUB_REF'));
\define('CORE_GITHUB_EVENT_PATH', getenv('GITHUB_EVENT_PATH'));
\define('CORE_GITHUB_HEAD_REF', getenv('GITHUB_HEAD_REF'));
\define('CORE_RUNNER_OS', getenv('RUNNER_OS'));
