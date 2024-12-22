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

    public function __construct(PimpleContainer $container)
    {
        $this->container = $container;
    }

    public function getContainer(): PimpleContainer
    {
        return $this->container;
    }

    public function getPsrContainer(): PsrContainer
    {
        return new PsrContainer($this->container);
    }

    public function add(string $key, callable $binding)
    {
        $this->container[$key] = $binding;

        return $this->container;
    }

    public static function init(PimpleContainer $container): self
    {
        $bindings = new self($container);

        $bindings->coreBindings();

        return $bindings;
    }

    public function coreBindings(): void
    {
        $this->add('filesystem', function () {
            return new Filesystem();
        });

        $this->add('configs', function ($c) {
            return Configs::init(APP_DIR_PATH);
        });

        $this->add('const_builder', function ($c) {
            return new ConstantBuilder();
        });

        $this->add('kernel', function ($c) {
            return new KernelConfig($c['const_builder']);
        });

        $this->add('site_manager', function ($c) {
            return new SiteManager($c['const_builder']);
        });

        $this->add('switcher', function ($c) {
            return new Switcher($c['const_builder']);
        });

        $this->add('auth', function ($c) {
            return new AuthManager();
        });

        $this->add('logger', function ($c) {
            return new FileLogger();
        });

        $this->add('middlewares', function ($c) {
            return new CoreMiddleware($c);
        });

        $this->add('whoops', function ($c) {
            return new WhoopRunner();
        });
    }
}
