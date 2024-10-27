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
use WPframework\Middleware\AbstractMiddleware;

/**
 * Tests for AbstractMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\AbstractMiddleware
 *
 * @internal
 */
class AbstractMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\AbstractMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\AbstractMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\AbstractMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\AbstractMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\AbstractMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\AbstractMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\AbstractMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\AbstractMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\AbstractMiddleware', 'multiMerge'));
    }
}
