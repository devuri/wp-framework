<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Middleware\Handlers;

use PHPUnit\Framework\TestCase;
use WPframework\Middleware\Handlers\MiddlewareRegistry;

/**
 * Tests for MiddlewareRegistry.
 *
 * @group WPframework\Middleware\Handlers
 *
 * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry
 *
 * @internal
 */
class MiddlewareRegistryTest extends TestCase
{
    public function setUp(): void
    {
        // self::markTestIncomplete();
    }

    /**
     * @incomplete
     */
    public function test_class_exists(): void
    {
        self::assertTrue(class_exists('WPframework\Middleware\Handlers\MiddlewareRegistry'));
    }


    /**
     * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry::register
     */
    public function test_register(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\MiddlewareRegistry', 'register'));
    }
    /**
     * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry::getRegisteredMiddleware
     */
    public function test_get_registered_middleware(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\MiddlewareRegistry', 'getRegisteredMiddleware'));
    }
    /**
     * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry::setDefault
     */
    public function test_set_default(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\MiddlewareRegistry', 'setDefault'));
    }
    /**
     * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry::getDefaults
     */
    public function test_get_defaults(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\MiddlewareRegistry', 'getDefaults'));
    }
    /**
     * @covers \WPframework\Middleware\Handlers\MiddlewareRegistry::configManager
     */
    public function test_config_manager(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\Handlers\MiddlewareRegistry', 'configManager'));
    }
}
