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
use Psr\Http\Message\UriInterface;
use WPframework\Http\Message\Uri;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Uri
 *
 * @internal
 */
class UriTest extends TestCase
{
    public function test_it_creates_instance_of_uri_interface(): void
    {
        $uri = new Uri('https://example.com');
        $this->assertInstanceOf(UriInterface::class, $uri);
    }

    public function test_it_returns_scheme(): void
    {
        $uri = new Uri('https://example.com');
        $this->assertEquals('https', $uri->getScheme());
    }

    public function test_it_returns_host(): void
    {
        $uri = new Uri('https://example.com');
        $this->assertEquals('example.com', $uri->getHost());
    }

    public function test_it_can_change_path(): void
    {
        $uri = new Uri('https://example.com');
        $uri = $uri->withPath('/new-path');
        $this->assertEquals('/new-path', $uri->getPath());
    }
}
