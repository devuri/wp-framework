<?php

declare(strict_types=1);

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Logger;

/*
 * Tests for FileLogger.
 *
 * @group WPframework\Logger
 *
 * @covers WPframework\Logger\FileLogger
 *
 * @internal
 */
use PHPUnit\Framework\TestCase;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

/**
 * @group WPframework\Logger
 *
 * @covers \WPframework\Logger\FileLogger
 *
 * @internal
 */
class FileLoggerTest extends TestCase
{
    private $logFile;

    protected function setUp(): void
    {
        $this->ErrorLogFile = APP_TEST_PATH . '/errorlog-test.log';
        $this->logFile = APP_TEST_PATH . '/test.log';

        Log::createLogFile($this->ErrorLogFile);
        ini_set('error_log', $this->ErrorLogFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        if (file_exists($this->ErrorLogFile)) {
            unlink($this->ErrorLogFile);
        }

        ini_restore('error_log');
    }

    /**
     * Test that logs are written to a file.
     */
    public function test_log_to_file(): void
    {
        $logger = new FileLogger($this->logFile);
        $logger->info('Test log to file');

        // Assert that the file exists and contains the log entry
        $this->assertFileExists($this->logFile);

        // Check if the log file contains the correct log entry
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('INFO: Test log to file', $logContent);
    }

    /**
     * Test logging with context interpolation.
     */
    public function test_log_with_interpolation(): void
    {
        $logger = new FileLogger($this->logFile);
        $logger->error('An error occurred: {error}', ['error' => 'File not found']);

        // Assert that the log file contains the interpolated message
        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('ERROR: An error occurred: File not found', $logContent);
    }

    /**
     * Test fallback to error_log when no file is provided.
     */
    public function test_fallback_to_error_log(): void
    {
        $logger = new FileLogger();

        $logger->warning('Test log to error_log');

        $errorLogContent = file_get_contents($this->ErrorLogFile);
        $this->assertStringContainsString('WARNING: Test log to error_log', $errorLogContent);
    }

    /**
     * Test invalid log level throws an exception.
     */
    public function test_invalid_log_level_throws_exception(): void
    {
        $this->expectException(\Psr\Log\InvalidArgumentException::class);

        $logger = new FileLogger($this->logFile);
        $logger->log('invalid_level', 'This should throw an exception');
    }

    /**
     * Test logging without a writable file defaults to error_log.
     */
    public function test_non_writable_file_falls_back_to_error_log(): void
    {
        $logger = new FileLogger('/invalid/path/test.log');

        $logger->critical('This should fall back to error_log');

        $errorLogContent = file_get_contents($this->ErrorLogFile);
        $this->assertStringContainsString('CRITICAL: This should fall back to error_log', $errorLogContent);
    }

    /**
     * Test all log levels are supported.
     */
    public function test_all_log_levels(): void
    {
        $logger = new FileLogger($this->logFile);

        $logger->emergency('Emergency log');
        $logger->alert('Alert log');
        $logger->critical('Critical log');
        $logger->error('Error log');
        $logger->warning('Warning log');
        $logger->notice('Notice log');
        $logger->info('Info log');
        $logger->debug('Debug log');

        // Verify that all log levels are written to the file
        $logContent = file_get_contents($this->logFile);

        $this->assertStringContainsString('EMERGENCY: Emergency log', $logContent);
        $this->assertStringContainsString('ALERT: Alert log', $logContent);
        $this->assertStringContainsString('CRITICAL: Critical log', $logContent);
        $this->assertStringContainsString('ERROR: Error log', $logContent);
        $this->assertStringContainsString('WARNING: Warning log', $logContent);
        $this->assertStringContainsString('NOTICE: Notice log', $logContent);
        $this->assertStringContainsString('INFO: Info log', $logContent);
        $this->assertStringContainsString('DEBUG: Debug log', $logContent);
    }
}
