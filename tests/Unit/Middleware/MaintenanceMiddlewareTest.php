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
use WPframework\Middleware\MaintenanceMiddleware;

/**
 * Tests for MaintenanceMiddleware.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\MaintenanceMiddleware
 *
 * @internal
 */
class MaintenanceMiddlewareTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\MaintenanceMiddleware'));
    }


    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::inMaintenanceMode
     */
    public function test_in_maintenance_mode(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'inMaintenanceMode'));
    }
    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::getMaintenanceMessage
     */
    public function test_get_maintenance_message(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'getMaintenanceMessage'));
    }
    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\MaintenanceMiddleware::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\MaintenanceMiddleware', 'multiMerge'));
    }
}
