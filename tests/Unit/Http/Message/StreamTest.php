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
use WPframework\Http\Message\Stream;

/**
 * Tests for Stream.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\Stream
 *
 * @internal
 */
class StreamTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\Stream'));
    }


    /**
     * @covers \WPframework\Http\Message\Stream::create
     */
    public function test_create(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'create'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::close
     */
    public function test_close(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'close'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::detach
     */
    public function test_detach(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'detach'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::getSize
     */
    public function test_get_size(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'getSize'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::tell
     */
    public function test_tell(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'tell'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::eof
     */
    public function test_eof(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'eof'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::isSeekable
     */
    public function test_is_seekable(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'isSeekable'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::seek
     */
    public function test_seek(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'seek'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::rewind
     */
    public function test_rewind(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'rewind'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::isWritable
     */
    public function test_is_writable(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'isWritable'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::write
     */
    public function test_write(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'write'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::isReadable
     */
    public function test_is_readable(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'isReadable'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::read
     */
    public function test_read(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'read'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::getContents
     */
    public function test_get_contents(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'getContents'));
    }
    /**
     * @covers \WPframework\Http\Message\Stream::getMetadata
     */
    public function test_get_metadata(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\Stream', 'getMetadata'));
    }
}
