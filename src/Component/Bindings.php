<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use Closure;
use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container as PsrContainer;
use Symfony\Component\Filesystem\Filesystem;
use Whoops\Run as WhoopRunner;
use WPframework\Logger\FileLogger;
use WPframework\Middleware\Handlers\CoreMiddleware;
use WPframework\Support\Configs;
use WPframework\Support\ConstantBuilder;
use WPframework\Support\KernelConfig;
use WPframework\Support\Services\AuthManager;
use WPframework\Support\SiteManager;
use WPframework\Support\Switcher;

class Bindings
{
    /**
     * @var PimpleContainer
     */
    protected $container;

    /**
     * Bindings constructor.
     *
     * @param PimpleContainer $container
     */
    public function __construct(PimpleContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Get the Pimple container instance.
     *
     * @return PimpleContainer
     */
    public function getContainer(): PimpleContainer
    {
        return $this->container;
    }

    /**
     * Convert to a PSR-compatible container.
     *
     * @return PsrContainer
     */
    public function getPsrContainer(): PsrContainer
    {
        return new PsrContainer($this->container);
    }

    /**
     * Add a binding to the container.
     *
     * @param string   $key
     * @param callable $binding
     *
     * @return PimpleContainer
     */
    public function add(string $key, callable $binding): PimpleContainer
    {
        $this->container[$key] = $binding;

        return $this->container;
    }

    /**
     * Initialize the bindings instance with a container.
     *
     * @param PimpleContainer $container
     *
     * @return self
     */
    public static function init(PimpleContainer $container): self
    {
        $bindings = new self($container);

        $bindings->registerBindings($bindings->getCoreBindings());

        return $bindings;
    }

    /**
     * Register multiple bindings at once.
     *
     * @param array $bindings
     */
    public function registerBindings(array $bindings): void
    {
        foreach ($bindings as $key => $binding) {
            $this->add($key, $binding);
        }
    }

    /**
     * Get an array of core bindings.
     *
     * @return (Closure)[]
     *
     * @psalm-return array{filesystem: \Closure():Filesystem, configs: \Closure(mixed):Configs, const_builder: \Closure(mixed):ConstantBuilder, kernel: \Closure(mixed):KernelConfig, site_manager: \Closure(mixed):SiteManager, switcher: \Closure(mixed):Switcher, auth: \Closure(mixed):AuthManager, logger: \Closure(mixed):FileLogger, middlewares: \Closure(mixed):CoreMiddleware, whoops: \Closure(mixed):WhoopRunner}
     */
    public function getCoreBindings(): array
    {
        return [
            'filesystem' => function () {
                return new Filesystem();
            },
            'configs' => function ($c) {
                return Configs::init(APP_DIR_PATH);
            },
			'env_type' => function ($c) {
                return new EnvType($c['filesystem']);
            },
            'const_builder' => function ($c) {
                return new ConstantBuilder();
            },
            'kernel' => function ($c) {
                return new KernelConfig($c['const_builder']);
            },
            'site_manager' => function ($c) {
                return new SiteManager($c['const_builder']);
            },
            'switcher' => function ($c) {
                return new Switcher($c['const_builder']);
            },
            'auth' => function ($c) {
                return new AuthManager();
            },
            'logger' => function ($c) {
                return new FileLogger();
            },
            'middlewares' => function ($c) {
                return new CoreMiddleware($c);
            },
            'whoops' => function ($c) {
                return new WhoopRunner();
            },
        ];
    }
}
