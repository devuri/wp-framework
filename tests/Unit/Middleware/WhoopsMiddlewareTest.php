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
use WPframework\Middleware\WhoopsMiddleware;

/**
 * Tests for WhoopsMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\WhoopsMiddleware
 *
 * @internal
 */
class WhoopsMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\WhoopsMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\WhoopsMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\WhoopsMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\WhoopsMiddleware::handleException
     */
    public function test_handle_exception(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\WhoopsMiddleware', 'handleException'));
    }
}
