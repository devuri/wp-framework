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
use WPframework\Http\Message\Stream;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Stream
 *
 * @internal
 */
class StreamTest extends TestCase
{
    public function test_it_creates_instance_of_stream_interface(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function test_it_can_write_to_stream(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $stream->write('Hello, World!');

        $stream->rewind();
        $this->assertEquals('Hello, World!', $stream->getContents());
    }

    // public function testItCanReadStreamContents()
    // {
    //     $stream = Stream::create('php://memory', 'r+');
    //     $stream->write('Test stream');
    //
    //     $stream->rewind();
    //     $this->assertEquals('Test stream', $stream->getContents());
    // }
}
