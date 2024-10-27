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
use WPframework\Middleware\FirewallMiddleware;

/**
 * Tests for FirewallMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\FirewallMiddleware
 *
 * @internal
 */
class FirewallMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\FirewallMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\FirewallMiddleware::isBot
     */
    public function test_is_bot(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FirewallMiddleware', 'isBot'));
    }
    /**
     * @covers \WPframework\Middleware\FirewallMiddleware::getUserAgent
     */
    public function test_get_user_agent(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FirewallMiddleware', 'getUserAgent'));
    }
    /**
     * @covers \WPframework\Middleware\FirewallMiddleware::getClientHints
     */
    public function test_get_client_hints(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FirewallMiddleware', 'getClientHints'));
    }
    /**
     * @covers \WPframework\Middleware\FirewallMiddleware::initializeDeviceDetector
     */
    public function test_initialize_device_detector(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FirewallMiddleware', 'initializeDeviceDetector'));
    }
}
