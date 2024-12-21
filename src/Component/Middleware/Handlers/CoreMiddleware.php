<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware\Handlers;

use WPframework\Middleware\AuthMiddleware;
use WPframework\Middleware\ConstMiddleware;
use WPframework\Middleware\HttpsOnlyMiddleware;
use WPframework\Middleware\IgnitionMiddleware;
use WPframework\Middleware\KernelMiddleware;
use WPframework\Middleware\LoggingMiddleware;
use WPframework\Middleware\SecurityHeadersMiddleware;
use WPframework\Middleware\SpamDetectionMiddleware;
use WPframework\Middleware\StatusMiddleware;
use WPframework\Middleware\TenantIdMiddleware;
use WPframework\Middleware\WhoopsMiddleware;

class CoreMiddleware
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        return [
            'security' => SecurityHeadersMiddleware::class,
            // 'https' => HttpsOnlyMiddleware::class,
            // 'spam' => SpamDetectionMiddleware::class,
            'tenant' => TenantIdMiddleware::class,
            'ignit' => IgnitionMiddleware::class,
            'status' => StatusMiddleware::class,
            'config' => ConstMiddleware::class,
            'kernel' => KernelMiddleware::class,
            'auth' => AuthMiddleware::class,
            'logger' => LoggingMiddleware::class,
            'whoops' => WhoopsMiddleware::class,
        ];
    }
}
