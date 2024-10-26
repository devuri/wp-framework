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
use WPframework\Http\Message\ServerRequest;

/**
 * Tests for ServerRequest.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\ServerRequest
 *
 * @internal
 */
class ServerRequestTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\ServerRequest'));
    }


    /**
     * @covers \WPframework\Http\Message\ServerRequest::getServerParams
     */
    public function test_get_server_params(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getServerParams'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getUploadedFiles
     */
    public function test_get_uploaded_files(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getUploadedFiles'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withUploadedFiles
     */
    public function test_with_uploaded_files(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withUploadedFiles'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getCookieParams
     */
    public function test_get_cookie_params(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getCookieParams'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withCookieParams
     */
    public function test_with_cookie_params(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withCookieParams'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getQueryParams
     */
    public function test_get_query_params(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getQueryParams'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withQueryParams
     */
    public function test_with_query_params(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withQueryParams'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getParsedBody
     */
    public function test_get_parsed_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getParsedBody'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withParsedBody
     */
    public function test_with_parsed_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withParsedBody'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getAttributes
     */
    public function test_get_attributes(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getAttributes'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getAttribute
     */
    public function test_get_attribute(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getAttribute'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withAttribute
     */
    public function test_with_attribute(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withAttribute'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withoutAttribute
     */
    public function test_without_attribute(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withoutAttribute'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getProtocolVersion
     */
    public function test_get_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withProtocolVersion
     */
    public function test_with_protocol_version(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withProtocolVersion'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getHeaders
     */
    public function test_get_headers(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getHeaders'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::hasHeader
     */
    public function test_has_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'hasHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getHeader
     */
    public function test_get_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getHeaderLine
     */
    public function test_get_header_line(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getHeaderLine'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withHeader
     */
    public function test_with_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withAddedHeader
     */
    public function test_with_added_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withAddedHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withoutHeader
     */
    public function test_without_header(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withoutHeader'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getBody
     */
    public function test_get_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getBody'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withBody
     */
    public function test_with_body(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withBody'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getRequestTarget
     */
    public function test_get_request_target(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getRequestTarget'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withRequestTarget
     */
    public function test_with_request_target(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withRequestTarget'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getMethod
     */
    public function test_get_method(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getMethod'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withMethod
     */
    public function test_with_method(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withMethod'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::getUri
     */
    public function test_get_uri(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'getUri'));
    }
    /**
     * @covers \WPframework\Http\Message\ServerRequest::withUri
     */
    public function test_with_uri(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\ServerRequest', 'withUri'));
    }
}
