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

use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WPframework\Http\Message\ServerRequest;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\ServerRequest
 *
 * @internal
 */
class ServerRequestTest extends TestCase
{
    public function test_it_creates_instance_of_server_request_interface(): void
    {
        $request = new ServerRequest('GET', new Uri('https://example.com'));
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
    }

    public function test_it_returns_method(): void
    {
        $request = new ServerRequest('POST', new Uri('https://example.com'));
        $this->assertEquals('POST', $request->getMethod());
    }

    public function test_it_can_add_headers(): void
    {
        $request = new ServerRequest('GET', new Uri('https://example.com'));
        $request = $request->withHeader('X-Test', 'test-value');
        $this->assertEquals(['test-value'], $request->getHeader('X-Test'));
    }

    public function test_it_can_retrieve_query_params(): void
    {
        $request = new ServerRequest('GET', new Uri('https://example.com?foo=bar'));
        $this->assertEquals(['foo' => 'bar'], $request->getQueryParams());
    }
}
