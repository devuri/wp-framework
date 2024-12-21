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

require_once \dirname(__FILE__, 2) . '/vendor/autoload.php';

\define('WP_DEBUG', false);

$siteAppFactory = AppFactory::create(__DIR__);

// AppFactory::run();
