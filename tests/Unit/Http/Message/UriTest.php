<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Http\Message;

use PHPUnit\Framework\TestCase;
use WPframework\Http\Message\Uri;

/**
 * Tests for Uri.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Uri
 *
 * @internal
 */
class UriTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\Uri'));
    }


    /**
     * @covers \WPframework\Http\Message\Uri::getScheme
     */
    public function test_get_scheme(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getScheme'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getAuthority
     */
    public function test_get_authority(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getAuthority'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getUserInfo
     */
    public function test_get_user_info(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getUserInfo'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getHost
     */
    public function test_get_host(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getHost'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getPort
     */
    public function test_get_port(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getPort'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getPath
     */
    public function test_get_path(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getPath'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getQuery
     */
    public function test_get_query(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getQuery'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::getFragment
     */
    public function test_get_fragment(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'getFragment'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withScheme
     */
    public function test_with_scheme(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withScheme'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withUserInfo
     */
    public function test_with_user_info(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withUserInfo'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withHost
     */
    public function test_with_host(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withHost'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withPort
     */
    public function test_with_port(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withPort'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withPath
     */
    public function test_with_path(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withPath'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withQuery
     */
    public function test_with_query(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withQuery'));
    }
    /**
     * @covers \WPframework\Http\Message\Uri::withFragment
     */
    public function test_with_fragment(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Uri', 'withFragment'));
    }
}
