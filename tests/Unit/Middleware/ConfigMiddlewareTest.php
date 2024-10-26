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
use WPframework\Middleware\ConfigMiddleware;

/**
 * Tests for ConfigMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\ConfigMiddleware
 *
 * @internal
 */
class ConfigMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\ConfigMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\ConfigMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConfigMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\ConfigMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConfigMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\ConfigMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConfigMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\ConfigMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConfigMiddleware', 'multiMerge'));
    }
}
