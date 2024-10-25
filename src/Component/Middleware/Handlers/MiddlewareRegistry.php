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

use WPframework\Middleware\Traits\CoreMiddlewareTrait;

class MiddlewareRegistry
{
    use CoreMiddlewareTrait;

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
     * @return array
     */
    public function getRegisteredMiddleware(): array
    {
        return $this->middlewares;
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
}
