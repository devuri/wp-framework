<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Middleware\Handlers;

use Pimple\Container as PimpleContainer;

class CoreMiddleware
{
    /**
     * @var null|array
     */
    protected $middlewares;

    /**
     * @var null|array
     */
    protected $configs;

    public function __construct(PimpleContainer $container)
    {
        $this->configs = $container['configs'];
        $this->middlewares = $this->configs->config['middlewares'];
    }

    /**
     * @return array
     */
    public function getAll(?array $enabledKeys = null): array
    {
        if (\is_null($enabledKeys)) {
            $enabledKeys = $this->defaultMiddlewareKeys();
        }

        return $this->filter($enabledKeys);
    }

    /**
     * Filter middlewares to include only the enabled.
     *
     * @return array
     */
    public function filter(array $enabledKeys): array
    {
        return array_filter($this->middlewares, function ($key) use ($enabledKeys) {
            return \in_array($key, $enabledKeys, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Fetch enabled middleware keys.
     *
     * @return string[]
     *
     * @psalm-return list{'security', 'tenant', 'ignit', 'kiosk', 'status', 'config', 'kernel', 'auth', 'logger', 'shortinit', 'adminer', 'whoops'}
     */
    private function defaultMiddlewareKeys(): array
    {
        return [
            'security',
            'tenant',
            'ignit',
            'kiosk',
            'status',
            'config',
            'kernel',
            'auth',
            'logger',
            'shortinit',
            'adminer',
            'webhook',
            'whoops',
        ];
    }
}
