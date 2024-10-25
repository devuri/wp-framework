<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware\Traits;

use Whoops\Run;
use WPframework\AppConfig;
use WPframework\ConstantBuilder;
use WPframework\Logger\FileLogger;
use WPframework\Middleware\ConfigMiddleware;
use WPframework\Middleware\DotenvMiddleware;
use WPframework\Middleware\ErrorHandlerMiddleware;
use WPframework\Middleware\KernelMiddleware;
use WPframework\Middleware\LoggingMiddleware;
use WPframework\Middleware\MaintenanceMiddleware;
use WPframework\Middleware\WhoopsMiddleware;
use WPframework\Support\KernelConfig;

trait CoreMiddlewareTrait
{
    protected static function getDefaults(): array
    {
        return [
            'dotenv' => DotenvMiddleware::class,
            'whoops' => new WhoopsMiddleware(new Run()),
            'error' => ErrorHandlerMiddleware::class,
            'config' => new ConfigMiddleware(self::configManager()),
            'kernel' => new KernelMiddleware(new KernelConfig(self::configManager())),
            'maintenance' => MaintenanceMiddleware::class,
            'logger' => new LoggingMiddleware(new FileLogger()),
        ];
    }

    protected static function configManager(): AppConfig
    {
        return new AppConfig(new ConstantBuilder());
    }
}
