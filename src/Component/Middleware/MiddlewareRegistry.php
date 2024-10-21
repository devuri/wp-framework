<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware;

use WPframework\Logger\FileLogger;

class MiddlewareRegistry
{
    protected ?array $middlewares;

    public function __construct()
    {
        $this->setDefault();
    }

    /**
     * Register middleware by class name.
     *
     * @param callable|MiddlewareInterface $middleware
     */
    public function register($middleware, string $key): void
    {
        $this->middlewares[$key] = $middleware;
    }

    /**
     * Return all registered middleware instances.
     *
     * @return array
     */
    public function getRegisteredMiddleware(): array
    {
        return $this->middlewares;

        // return array_map(function ($middleware) {
        //     return new $middleware();
        // }, $this->middlewares);
    }

    protected function setDefault(): void
    {
        foreach (self::getDefaults() as $key => $middleware) {
            if ( ! \is_string($key)) {
                continue;
            }
            $this->register($middleware, $key);
        }
    }

    protected static function getDefaults(): array
    {
        return [
            'dotenv' => DotenvMiddleware::class,
            'multitenant' => MultiTenantMiddleware::class,
            'config' => ConfigMiddleware::class,
            'environment' => EnvironmentMiddleware::class,
            'logger' => new LoggingMiddleware(new FileLogger()),
        ];
    }
}
