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
use Psr\Http\Message\RequestInterface;
use WPframework\Http\Message\Request;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Request
 *
 * @internal
 */
class RequestTest extends TestCase
{
    public function test_it_creates_instance_of_request_interface(): void
    {
        $request = new Request('GET', new Uri('https://example.com'));
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function test_it_returns_uri(): void
    {
        $uri = new Uri('https://example.com');
        $request = new Request('GET', $uri);
        $this->assertEquals($uri, $request->getUri());
    }

    public function test_it_can_change_method(): void
    {
        $request = new Request('GET', new Uri('https://example.com'));
        $request = $request->withMethod('POST');
        $this->assertEquals('POST', $request->getMethod());
    }
}
