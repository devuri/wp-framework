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

return [
    'filesystem' => function () {
        return new Filesystem();
    },

    'configs' => function ($c) {
        return Configs::init(APP_DIR_PATH);
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
