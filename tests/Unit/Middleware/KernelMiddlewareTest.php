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
use WPframework\Middleware\KernelMiddleware;

/**
 * Tests for KernelMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\KernelMiddleware
 *
 * @internal
 */
class KernelMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\KernelMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\KernelMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\KernelMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\KernelMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\KernelMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\KernelMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\KernelMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\KernelMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\KernelMiddleware', 'multiMerge'));
    }
}
