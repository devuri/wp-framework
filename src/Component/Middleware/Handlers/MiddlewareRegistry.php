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

use Psr\Http\Server\MiddlewareInterface;

class MiddlewareRegistry
{
    protected ?array $middlewares;

    public function __construct()
    {
        $this->setDefault(new CoreMiddleware());
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
     * @return null|array
     */
    public function getRegisteredMiddleware(): ?array
    {
        return $this->middlewares;
    }

    protected function setDefault(CoreMiddleware $core): void
    {
        foreach ($core->getAll() as $key => $middleware) {
            if ( ! \is_string($key) || empty($key)) {
                continue;
            }
            $this->register($middleware, $key);
        }
    }
}
