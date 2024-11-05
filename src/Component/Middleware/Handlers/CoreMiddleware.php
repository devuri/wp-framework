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

use Whoops\Run;
use WPframework\Logger\FileLogger;
use WPframework\Middleware\ConfigMiddleware;
use WPframework\Middleware\DotenvMiddleware;
use WPframework\Middleware\KernelMiddleware;
use WPframework\Middleware\LoggingMiddleware;
use WPframework\Middleware\SecurityHeadersMiddleware;
use WPframework\Middleware\SpamDetectionMiddleware;
use WPframework\Middleware\WhoopsMiddleware;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\KernelConfig;

class CoreMiddleware
{
    /**
     * @return (\WPframework\Middleware\ConfigMiddleware|\WPframework\Middleware\DotenvMiddleware|\WPframework\Middleware\KernelMiddleware|\WPframework\Middleware\LoggingMiddleware|\WPframework\Middleware\WhoopsMiddleware)[]
     *
     * @psalm-return array{dotenv: \WPframework\Middleware\DotenvMiddleware, config: \WPframework\Middleware\ConfigMiddleware, kernel: \WPframework\Middleware\KernelMiddleware, logger: \WPframework\Middleware\LoggingMiddleware, whoops: \WPframework\Middleware\WhoopsMiddleware}
     */
    public function getAll(): array
    {
        return [
            'security' => SecurityHeadersMiddleware::class,
            // 'spam' => SpamDetectionMiddleware::class,
            'dotenv' => new DotenvMiddleware(),
            'config' => new ConfigMiddleware(self::configManager()),
            'kernel' => new KernelMiddleware($this->kernelConfig()),
            'logger' => new LoggingMiddleware(new FileLogger()),
            'whoops' => new WhoopsMiddleware(new Run()),
        ];
    }

    protected function kernelConfig(): KernelConfig
    {
        return new KernelConfig(self::configManager());
    }

    protected static function configManager(): ConstantBuilder
    {
        return new ConstantBuilder();
    }
}
