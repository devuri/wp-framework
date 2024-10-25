<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Tests\Unit\Component\Logger;

use PHPUnit\Framework\TestCase;
use WPframework\Logger\FileLogger;
use WPframework\Logger\Log;

/**
 * @internal
 *
 * @coversNothing
 */
class LogTest extends TestCase
{
    private $logFile;
    private $customErrorLog;

    protected function setUp(): void
    {
        $this->logFile = APP_TEST_PATH . '/test.log';
        Log::init(new FileLogger($this->logFile));

        $this->customErrorLog = APP_TEST_PATH . '/error.log';
        Log::createLogFile($this->customErrorLog);
        ini_set('error_log', $this->customErrorLog);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        if (file_exists($this->customErrorLog)) {
            unlink($this->customErrorLog);
        }

        ini_restore('error_log');
    }

    /**
     * Test logging an emergency level message.
     */
    public function test_log_emergency(): void
    {
        Log::emergency('Emergency situation');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('EMERGENCY: Emergency situation', $logContent);
    }

    /**
     * Test logging an alert level message.
     */
    public function test_log_alert(): void
    {
        Log::alert('Alert situation');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('ALERT: Alert situation', $logContent);
    }

    /**
     * Test logging a critical level message.
     */
    public function test_log_critical(): void
    {
        Log::critical('Critical issue');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('CRITICAL: Critical issue', $logContent);
    }

    /**
     * Test logging an error level message.
     */
    public function test_log_error(): void
    {
        Log::error('An error occurred');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('ERROR: An error occurred', $logContent);
    }

    /**
     * Test logging a warning level message.
     */
    public function test_log_warning(): void
    {
        Log::warning('Warning: Check this out');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('WARNING: Warning: Check this out', $logContent);
    }

    /**
     * Test logging a notice level message.
     */
    public function test_log_notice(): void
    {
        Log::notice('Notice: Just for information');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('NOTICE: Notice: Just for information', $logContent);
    }

    /**
     * Test logging an info level message.
     */
    public function test_log_info(): void
    {
        Log::info('Informational message');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('INFO: Informational message', $logContent);
    }

    /**
     * Test logging a debug level message.
     */
    public function test_log_debug(): void
    {
        Log::debug('Debugging the system');

        $logContent = file_get_contents($this->logFile);
        $this->assertStringContainsString('DEBUG: Debugging the system', $logContent);
    }

    /**
     * Test fallback to error_log if no file is provided.
     */
    public function test_fallback_to_error_log(): void
    {
        Log::init(new FileLogger());

        Log::warning('This log goes to error_log');

        $this->assertFileExists($this->customErrorLog);
        $logContent = file_get_contents($this->customErrorLog);
        $this->assertStringContainsString('WARNING: This log goes to error_log', $logContent);
    }

    /**
     * Test logging all log levels.
     */
    public function test_log_all_levels(): void
    {
        Log::emergency('Emergency log');
        Log::alert('Alert log');
        Log::critical('Critical log');
        Log::error('Error log');
        Log::warning('Warning log');
        Log::notice('Notice log');
        Log::info('Info log');
        Log::debug('Debug log');

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
