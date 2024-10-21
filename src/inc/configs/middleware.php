<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'dotenv' => WPframework\Middleware\DotenvMiddleware::class,
    'favicon' => WPframework\Middleware\FaviconCache::class,
    'config' => WPframework\Middleware\ConfigMiddleware::class,
    'logger' => WPframework\Middleware\LoggingMiddleware::class,
    'environment' => WPframework\Middleware\EnvironmentMiddleware::class,
    'multitenant' => WPframework\Middleware\MultiTenantMiddleware::class,
];
