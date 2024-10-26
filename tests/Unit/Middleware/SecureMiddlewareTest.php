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
use WPframework\Middleware\SecureMiddleware;

/**
 * Tests for SecureMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\SecureMiddleware
 *
 * @internal
 */
class SecureMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\SecureMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\SecureMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\SecureMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\SecureMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\SecureMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\SecureMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\SecureMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\SecureMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\SecureMiddleware', 'multiMerge'));
    }
}
