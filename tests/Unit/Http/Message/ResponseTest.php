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
use WPframework\Http\Message\Response;

/**
 * Tests for Response.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Response
 *
 * @internal
 */
class ResponseTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\Response'));
    }


    /**
     * @covers \WPframework\Http\Message\Response::getStatusCode
     */
    public function test_get_status_code(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getStatusCode'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getReasonPhrase
     */
    public function test_get_reason_phrase(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getReasonPhrase'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withStatus
     */
    public function test_with_status(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withStatus'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getProtocolVersion
     */
    public function test_get_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withProtocolVersion
     */
    public function test_with_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getHeaders
     */
    public function test_get_headers(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getHeaders'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::hasHeader
     */
    public function test_has_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'hasHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getHeader
     */
    public function test_get_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getHeaderLine
     */
    public function test_get_header_line(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getHeaderLine'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withHeader
     */
    public function test_with_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withAddedHeader
     */
    public function test_with_added_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withAddedHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withoutHeader
     */
    public function test_without_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withoutHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::getBody
     */
    public function test_get_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'getBody'));
    }
    /**
     * @covers \WPframework\Http\Message\Response::withBody
     */
    public function test_with_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Response', 'withBody'));
    }
}
