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
use WPframework\Middleware\LoggingMiddleware;

/**
 * Tests for LoggingMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\LoggingMiddleware
 *
 * @internal
 */
class LoggingMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\LoggingMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\LoggingMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\LoggingMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\LoggingMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\LoggingMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\LoggingMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\LoggingMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\LoggingMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\LoggingMiddleware', 'multiMerge'));
    }
}
