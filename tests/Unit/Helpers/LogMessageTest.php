<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

/**
 * @group WPframework
 *
 * @covers ::logMessage
 *
 * @internal
 */
class LogMessageTest extends TestCase
{
    private $logFile;
    private $ErrorLogFile;
    private $customLogFile;

    protected function setUp(): void
    {
        $this->customLogFile = APP_TEST_PATH . '/custom.log';
        $this->ErrorLogFile = APP_TEST_PATH . '/errorlog-test.log';
        $this->logFile = APP_TEST_PATH . '/test.log';

        Log::createLogFile($this->customLogFile);

        Log::createLogFile($this->logFile);
        $log = Log::init(new FileLogger($this->logFile));

        Log::createLogFile($this->ErrorLogFile);
        ini_set('error_log', $this->ErrorLogFile);
    }

    protected function tearDown(): void
    {
        $this->deleteTestFiles();
        ini_restore('error_log');
    }

    /**
     * Test logging an info message with logMessage().
     */
    public function test_log_info_message(): void
    {
        logMessage('This is an info message', 'info', [], $this->logFile);

        $this->assertFileExists($this->logFile);
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('INFO: This is an info message', $logContent);
    }

    /**
     * Test logging an error message with context using logMessage().
     */
    public function test_log_error_with_context(): void
    {
        logMessage('Error occurred: {error}', 'error', ['error' => 'Something went wrong'], $this->logFile);

        $this->assertFileExists($this->logFile);
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('ERROR: Error occurred: Something went wrong', $logContent);
    }

    /**
     * Test logging to a custom file using logMessage().
     */
    public function test_log_to_custom_file(): void
    {
        logMessage('Debugging process', 'debug', [], $this->customLogFile);

        $this->assertFileExists($this->customLogFile);
        $logContent = file_get_contents($this->customLogFile);
        $this->assertStringContainsString('DEBUG: Debugging process', $logContent);
    }

    /**
     * Test logging fallback to error_log when no file is provided.
     */
    public function test_fallback_to_error_log(): void
    {
        logMessage('This is a warning message', 'warning');

        $ErrorLogContent = file_get_contents($this->ErrorLogFile);
        $this->assertStringContainsString('WARNING: This is a warning message', $ErrorLogContent);
    }

    /**
     * Test logging an invalid log level throws an exception.
     */
    public function test_invalid_log_level_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        logMessage('This should fail', 'invalid_level');
    }

    private function deleteTestFiles(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        if (file_exists($this->ErrorLogFile)) {
            unlink($this->ErrorLogFile);
        }

        if (file_exists($this->customLogFile)) {
            unlink($this->customLogFile);
        }
    }
}
