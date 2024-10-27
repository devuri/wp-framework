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
use WPframework\Http\Message\UploadedFile;

/**
 * Tests for UploadedFile.
 *
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\UploadedFile
 *
 * @internal
 */
class UploadedFileTest extends TestCase
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
        self::assertTrue(class_exists('WPframework\Http\Message\UploadedFile'));
    }


    /**
     * @covers \WPframework\Http\Message\UploadedFile::getStream
     */
    public function test_get_stream(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'getStream'));
    }
    /**
     * @covers \WPframework\Http\Message\UploadedFile::moveTo
     */
    public function test_move_to(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'moveTo'));
    }
    /**
     * @covers \WPframework\Http\Message\UploadedFile::getSize
     */
    public function test_get_size(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'getSize'));
    }
    /**
     * @covers \WPframework\Http\Message\UploadedFile::getError
     */
    public function test_get_error(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'getError'));
    }
    /**
     * @covers \WPframework\Http\Message\UploadedFile::getClientFilename
     */
    public function test_get_client_filename(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'getClientFilename'));
    }
    /**
     * @covers \WPframework\Http\Message\UploadedFile::getClientMediaType
     */
    public function test_get_client_media_type(): void
    {
        self::assertTrue(method_exists('WPframework\Http\Message\UploadedFile', 'getClientMediaType'));
    }
}
