<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use WPframework\Http\HostManager;

/**
 * Tests for HostManager.
 *
 * @group WPframework\Http
 *
 * @covers \WPframework\Http\HostManager
 *
 * @internal
 */
class HostManagerTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\HostManager'));
    }


    /**
     * @covers \WPframework\Http\HostManager::is_https_secure
     */
    public function test_is_https_secure(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HostManager', 'is_https_secure'));
    }
    /**
     * @covers \WPframework\Http\HostManager::get_http_host
     */
    public function test_get_http_host(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HostManager', 'get_http_host'));
    }
    /**
     * @covers \WPframework\Http\HostManager::get_server_host
     */
    public function test_get_server_host(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HostManager', 'get_server_host'));
    }
    /**
     * @covers \WPframework\Http\HostManager::get_request_url
     */
    public function test_get_request_url(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HostManager', 'get_request_url'));
    }
    /**
     * @covers \WPframework\Http\HostManager::sanitize_http_host
     */
    public function test_sanitize_http_host(): void
    {
        self::assertTrue(method_exists('WPframework\Http\HostManager', 'sanitize_http_host'));
    }
}
