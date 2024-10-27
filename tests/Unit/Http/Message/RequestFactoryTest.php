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
use WPframework\Http\Message\RequestFactory;

/**
 * Tests for RequestFactory.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\RequestFactory
 *
 * @internal
 */
class RequestFactoryTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\RequestFactory'));
    }


    /**
     * @covers \WPframework\Http\Message\RequestFactory::createRequest
     */
    public function test_create_request(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createRequest'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createResponse
     */
    public function test_create_response(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createResponse'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createStream
     */
    public function test_create_stream(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createStream'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createStreamFromFile
     */
    public function test_create_stream_from_file(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createStreamFromFile'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createStreamFromResource
     */
    public function test_create_stream_from_resource(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createStreamFromResource'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createUploadedFile
     */
    public function test_create_uploaded_file(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createUploadedFile'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createUri
     */
    public function test_create_uri(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createUri'));
    }
    /**
     * @covers \WPframework\Http\Message\RequestFactory::createServerRequest
     */
    public function test_create_server_request(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\RequestFactory', 'createServerRequest'));
    }
}
