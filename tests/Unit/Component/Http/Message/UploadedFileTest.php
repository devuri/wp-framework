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

use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use WPframework\Http\Message\UploadedFile;

/**
 * @group WPframework\Http\Message
 *
 * @covers \WPframework\Http\Message\UploadedFile
 *
 * @internal
 */
class UploadedFileTest extends TestCase
{
    public function test_it_creates_instance_of_uploaded_file_interface(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);
    }

    public function test_it_returns_file_size(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertEquals(100, $uploadedFile->getSize());
    }

    public function test_it_returns_client_filename(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');
        $this->assertEquals('test.txt', $uploadedFile->getClientFilename());
    }

    public function test_it_can_move_uploaded_file(): void
    {
        $stream = Stream::create('php://memory', 'r+');
        $uploadedFile = new UploadedFile($stream, 100, UPLOAD_ERR_OK, 'test.txt', 'text/plain');

        $tmpFile = tempnam(sys_get_temp_dir(), 'test');
        $uploadedFile->moveTo($tmpFile);

        $this->assertFileExists($tmpFile);
        unlink($tmpFile); // Cleanup
    }
}
