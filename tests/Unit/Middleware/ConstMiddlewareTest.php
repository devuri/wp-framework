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
use WPframework\Middleware\ConstMiddleware;

/**
 * Tests for ConstMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\ConstMiddleware
 *
 * @internal
 */
class ConstMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\ConstMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\ConstMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConstMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\ConstMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConstMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\ConstMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConstMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\ConstMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ConstMiddleware', 'multiMerge'));
    }
}
