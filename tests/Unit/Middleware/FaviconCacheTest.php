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
use WPframework\Middleware\FaviconCache;

/**
 * Tests for FaviconCache.
 *
 * @group WPframework\Middleware
 *
 * @covers \WPframework\Middleware\FaviconCache
 *
 * @internal
 */
class FaviconCacheTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Middleware\FaviconCache'));
    }


    /**
     * @covers \WPframework\Middleware\FaviconCache::process
     */
    public function test_process(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'process'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::isFaviconRequest
     */
    public function test_is_favicon_request(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'isFaviconRequest'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::handleFaviconRequest
     */
    public function test_handle_favicon_request(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'handleFaviconRequest'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::sendCacheHeaders
     */
    public function test_send_cache_headers(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'sendCacheHeaders'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::log
     */
    public function test_log(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'log'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::when
     */
    public function test_when(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'when'));
    }
    /**
     * @covers \WPframework\Middleware\FaviconCache::multiMerge
     */
    public function test_multi_merge(): void
    {
        self::assertTrue(method_exists('WPframework\Middleware\FaviconCache', 'multiMerge'));
    }
}
