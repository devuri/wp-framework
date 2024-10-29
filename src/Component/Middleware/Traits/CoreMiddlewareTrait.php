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
use WPframework\Logger\FileLogger;
use WPframework\Middleware\ConfigMiddleware;
use WPframework\Middleware\DotenvMiddleware;
use WPframework\Middleware\KernelMiddleware;
use WPframework\Middleware\LoggingMiddleware;
use WPframework\Middleware\WhoopsMiddleware;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\KernelConfig;

trait CoreMiddlewareTrait
{
    /**
     * @return (string|\WPframework\Middleware\ConfigMiddleware|\WPframework\Middleware\KernelMiddleware|\WPframework\Middleware\LoggingMiddleware|\WPframework\Middleware\WhoopsMiddleware)[]
     *
     * @psalm-return array{dotenv: \WPframework\Middleware\DotenvMiddleware::class, whoops: \WPframework\Middleware\WhoopsMiddleware, config: \WPframework\Middleware\ConfigMiddleware, kernel: \WPframework\Middleware\KernelMiddleware, logger: \WPframework\Middleware\LoggingMiddleware}
     */
    protected static function getDefaults(): array
    {
        return [
            'dotenv' => new DotenvMiddleware(),
            'config' => new ConfigMiddleware(self::configManager()),
            'kernel' => new KernelMiddleware(new KernelConfig(self::configManager())),
            'logger' => new LoggingMiddleware(new FileLogger()),
            'whoops' => new WhoopsMiddleware(new Run()),
        ];
    }

    protected static function configManager(): ConstantBuilder
    {
        return new ConstantBuilder();
    }
}
