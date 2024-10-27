<?php

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
use WPframework\Http\Message\Request;

/**
 * Tests for Request.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Request
 *
 * @internal
 */
class RequestTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\Request'));
    }


    /**
     * @covers \WPframework\Http\Message\Request::getProtocolVersion
     */
    public function test_get_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withProtocolVersion
     */
    public function test_with_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getHeaders
     */
    public function test_get_headers(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getHeaders'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::hasHeader
     */
    public function test_has_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'hasHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getHeader
     */
    public function test_get_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getHeaderLine
     */
    public function test_get_header_line(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getHeaderLine'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withHeader
     */
    public function test_with_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withAddedHeader
     */
    public function test_with_added_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withAddedHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withoutHeader
     */
    public function test_without_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withoutHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getBody
     */
    public function test_get_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getBody'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withBody
     */
    public function test_with_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withBody'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getRequestTarget
     */
    public function test_get_request_target(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getRequestTarget'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withRequestTarget
     */
    public function test_with_request_target(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withRequestTarget'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getMethod
     */
    public function test_get_method(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getMethod'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withMethod
     */
    public function test_with_method(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withMethod'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::getUri
     */
    public function test_get_uri(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'getUri'));
    }
    /**
     * @covers \WPframework\Http\Message\Request::withUri
     */
    public function test_with_uri(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Request', 'withUri'));
    }
}
