<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component\Http\Message;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use WPframework\Http\Message\Response;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Response
 *
 * @internal
 */
class ResponseTest extends TestCase
{
    /**
     * Test the constructor and default values.
     */
    public function test_constructor_sets_values(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, ['Content-Type' => ['application/json']], $body, '1.1', 'OK');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertSame($body, $response->getBody());
        $this->assertEquals('1.1', $response->getProtocolVersion());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    /**
     * Test the getProtocolVersion method.
     */
    public function test_get_protocol_version(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body, '1.0');

        $this->assertEquals('1.0', $response->getProtocolVersion());
    }

    /**
     * Test the withProtocolVersion method.
     */
    public function test_with_protocol_version(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body, '1.1');
        $newResponse = $response->withProtocolVersion('2.0');

        $this->assertEquals('2.0', $newResponse->getProtocolVersion());
        $this->assertNotSame($response, $newResponse); // Ensure immutability
    }

    /**
     * Test the getHeaders method.
     */
    public function test_get_headers(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, ['X-Test' => ['TestValue']], $body);

        $this->assertEquals(['X-Test' => ['TestValue']], $response->getHeaders());
    }

    /**
     * Test the hasHeader method.
     */
    public function test_has_header(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, ['X-Test' => ['TestValue']], $body);

        $this->assertTrue($response->hasHeader('X-Test'));
        $this->assertFalse($response->hasHeader('Non-Existent-Header'));
    }

    /**
     * Test the withHeader method.
     */
    public function test_with_header(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body);
        $newResponse = $response->withHeader('X-Test', 'NewValue');

        $this->assertNotSame($response, $newResponse); // Ensure immutability
        $this->assertEquals('NewValue', $newResponse->getHeaderLine('X-Test'));
    }

    /**
     * Test the withAddedHeader method.
     */
    public function test_with_added_header(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, ['X-Test' => ['Value1']], $body);
        $newResponse = $response->withAddedHeader('X-Test', 'Value2');

        $this->assertEquals('Value1, Value2', $newResponse->getHeaderLine('X-Test'));
    }

    /**
     * Test the withoutHeader method.
     */
    public function test_without_header(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, ['X-Test' => ['Value']], $body);
        $newResponse = $response->withoutHeader('X-Test');

        $this->assertFalse($newResponse->hasHeader('X-Test'));
    }

    /**
     * Test the getBody method.
     */
    public function test_get_body(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body);

        $this->assertSame($body, $response->getBody());
    }

    /**
     * Test the withBody method.
     */
    public function test_with_body(): void
    {
        $body1 = $this->createMock(StreamInterface::class);
        $body2 = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body1);
        $newResponse = $response->withBody($body2);

        $this->assertNotSame($response, $newResponse); // Ensure immutability
        $this->assertSame($body2, $newResponse->getBody());
    }

    /**
     * Test the getStatusCode method.
     */
    public function test_get_status_code(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(404, [], $body);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Test the withStatus method.
     */
    public function test_with_status(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body);
        $newResponse = $response->withStatus(404);

        $this->assertNotSame($response, $newResponse); // Ensure immutability
        $this->assertEquals(404, $newResponse->getStatusCode());
        $this->assertEquals('Not Found', $newResponse->getReasonPhrase());
    }

    /**
     * Test the getReasonPhrase method.
     */
    public function test_get_reason_phrase(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(200, [], $body, '1.1', 'OK');

        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    /**
     * Test status code to reason phrase mapping.
     */
    public function test_get_reason_phrase_for_status_code(): void
    {
        $body = $this->createMock(StreamInterface::class);
        $response = new Response(500, [], $body);

        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
    }
}
