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
use WPframework\Middleware\ErrorHandlerMiddleware;

/**
 * Tests for ErrorHandlerMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\ErrorHandlerMiddleware
 *
 * @internal
 */
class ErrorHandlerMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\ErrorHandlerMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::setErrorHandler
     */
    public function test_set_error_handler(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'setErrorHandler'));
    }
    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::enableErrorHandler
     */
    public function test_enable_error_handler(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'enableErrorHandler'));
    }
    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\ErrorHandlerMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\ErrorHandlerMiddleware', 'multiMerge'));
    }
}
