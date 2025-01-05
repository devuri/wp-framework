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
    /**
     * @var null|array
     */
    protected ?array $middlewares = [];

    public function __construct($container, ?array $filter = null)
    {
        $this->setDefault($container->get('middlewares'), $filter);
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
    public function getRegisteredMiddlewares(): ?array
    {
        return $this->middlewares;
    }

    protected function setDefault(CoreMiddleware $core, ?array $filter = null): void
    {
        $coreMiddlewares = $core->getAll($filter);

        foreach ($coreMiddlewares as $key => $middleware) {
            if (! \is_string($key) || empty($key)) {
                continue;
            }
            $this->register($middleware, $key);
        }
    }
}
