<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use WPframework\Middleware\DotenvMiddleware;

/**
 * Tests for DotenvMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\DotenvMiddleware
 *
 * @internal
 */
class DotenvMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\DotenvMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\DotenvMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\DotenvMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\DotenvMiddleware::tenantSetup
     */
    public function test_tenant_setup(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\DotenvMiddleware', 'tenantSetup'));
    }
    /**
     * @covers \WPframework\Middleware\DotenvMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\DotenvMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\DotenvMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\DotenvMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\DotenvMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\DotenvMiddleware', 'multiMerge'));
    }
}
