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
    'security' => WPframework\Middleware\SecurityHeadersMiddleware::class,
    'https' => WPframework\Middleware\HttpsOnlyMiddleware::class,
    'spam' => WPframework\Middleware\SpamDetectionMiddleware::class,
    'tenant' => WPframework\Middleware\TenantIdMiddleware::class,
    'ignit' => WPframework\Middleware\IgnitionMiddleware::class,
    'status' => WPframework\Middleware\StatusMiddleware::class,
    'config' => WPframework\Middleware\ConstMiddleware::class,
    'kernel' => WPframework\Middleware\KernelMiddleware::class,
    'auth' => WPframework\Middleware\AuthMiddleware::class,
    'logger' => WPframework\Middleware\LoggingMiddleware::class,
    'adminer' => WPframework\Middleware\AdminerMiddleware::class,
    'whoops' => WPframework\Middleware\WhoopsMiddleware::class,
];
